<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with(['branch', 'user'])->latest()->paginate(10);

        return view('stock_opnames.index', compact('opnames'));
    }

    public function create()
    {
        $branchId = Auth::user()->branch_id;
        $products = Product::where('branch_id', $branchId)->where('is_active', true)->get();

        return view('stock_opnames.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.physical_stock' => 'required|integer|min:0',
            'items.*.reason' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $opname = StockOpname::create([
                'branch_id' => Auth::user()->branch_id,
                'user_id' => Auth::id(),
                'reference_number' => 'AUDIT-'.strtoupper(Str::random(8)),
                'date' => $validated['date'],
                'status' => 'draft',
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $diff = $item['physical_stock'] - $product->stock;

                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'product_id' => $item['product_id'],
                    'system_stock' => $product->stock,
                    'physical_stock' => $item['physical_stock'],
                    'difference' => $diff,
                    'reason' => $item['reason'],
                ]);
            }

            return redirect()->route('stock-opnames.index')->with('success', 'Audit draft '.$opname->reference_number.' saved successfully.');
        });
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['branch', 'user', 'details.product']);

        return view('stock_opnames.show', compact('stockOpname'));
    }

    public function complete(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'completed') {
            return back()->with('error', 'Audit already completed and stock adjusted.');
        }

        return DB::transaction(function () use ($stockOpname) {
            foreach ($stockOpname->details as $detail) {
                if ($detail->difference != 0) {
                    $product = $detail->product;

                    // Direct set or increment/decrement
                    $oldStock = $product->stock;
                    $product->update(['stock' => $detail->physical_stock]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => $detail->difference > 0 ? 'in' : 'out',
                        'quantity' => abs($detail->difference),
                        'reference_type' => 'stock_opname',
                        'reference_id' => $stockOpname->id,
                        'notes' => 'Inventory adjustment via Audit '.$stockOpname->reference_number.'. From '.$oldStock.' to '.$detail->physical_stock,
                    ]);
                }
            }

            $stockOpname->update(['status' => 'completed']);

            return redirect()->route('stock-opnames.index')->with('success', 'Stock levels adjusted and audit closed.');
        });
    }

    public function destroy(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'completed') {
            return back()->with('error', 'Cannot delete a completed audit.');
        }
        $stockOpname->delete();

        return redirect()->route('stock-opnames.index')->with('success', 'Audit draft removed.');
    }
}
