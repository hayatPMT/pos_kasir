<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'branch', 'user'])->latest()->paginate(10);

        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $branches = Branch::all();
        $products = Product::where('is_active', true)->get();

        return view('purchase_orders.create', compact('suppliers', 'branches', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cost_price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['cost_price'];
            }

            $order = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'branch_id' => $validated['branch_id'],
                'user_id' => Auth::id(),
                'po_number' => 'PO-'.strtoupper(Str::random(8)),
                'order_date' => $validated['order_date'],
                'status' => 'ordered',
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'cost_price' => $item['cost_price'],
                    'subtotal' => $item['quantity'] * $item['cost_price'],
                ]);
            }

            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order '.$order->po_number.' created successfully.');
        });
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'branch', 'user', 'items.product']);

        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'Order already received.');
        }

        return DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);

                // Update buy price if cost price shifted significantly
                $product->update(['buy_price' => $item->cost_price]);

                // Log Movement
                \App\Models\StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'reference_type' => 'purchase_order',
                    'reference_id' => $purchaseOrder->id,
                    'notes' => 'Inventory restock via PO '.$purchaseOrder->po_number,
                ]);
            }

            $purchaseOrder->update([
                'status' => 'received',
                'delivery_date' => now(),
            ]);

            return redirect()->route('purchase-orders.index')->with('success', 'Inventory updated via PO '.$purchaseOrder->po_number);
        });
    }
}
