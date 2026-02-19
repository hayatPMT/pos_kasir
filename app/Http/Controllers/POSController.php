<?php

namespace App\Http\Controllers;

use App\Services\QRISService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class POSController extends Controller
{
    protected $transactionService;

    protected $qrisService;

    public function __construct(TransactionService $transactionService, QRISService $qrisService)
    {
        $this->transactionService = $transactionService;
        $this->qrisService = $qrisService;
    }

    public function index(Request $request): \Illuminate\Http\Response|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        // Require active shift for operational personnel
        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'open')
            ->first();

        if (! $activeShift && in_array(\Illuminate\Support\Facades\Auth::user()->role, ['cashier', 'branch_admin'])) {
            return redirect()->route('shifts.index')->with('error', 'Operational staff must start a shift before accessing the terminal.');
        }

        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::query()
            ->when($request->category, function ($query, $category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->where('is_active', true)
            ->get();

        $members = \App\Models\Member::all();
        $promotions = \App\Models\Promotion::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        return view('pos.index', compact('categories', 'products', 'members', 'promotions'));
    }

    public function kiosk(Request $request): \Illuminate\View\View
    {
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::where('is_active', true)->get();

        return view('pos.kiosk', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        // Absolute requirement: Active Shift
        $activeShift = \App\Models\Shift::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'open')
            ->first();

        if (! $activeShift) {
            return response()->json([
                'success' => false,
                'message' => 'No active shift found. Please open a shift first.',
            ], 403);
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'member_id' => 'nullable|exists:members,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'pay_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        try {
            $data = $validated;
            $data['branch_id'] = \Illuminate\Support\Facades\Auth::user()->branch_id;
            $data['user_id'] = \Illuminate\Support\Facades\Auth::id();

            // Handle QRIS specific state
            if ($data['payment_method'] === 'qris') {
                $data['status'] = 'pending';
            }

            $transaction = $this->transactionService->createTransaction($data);
            $transaction->load(['details.product', 'member', 'user', 'branch']);

            $responseData = ['data' => $transaction];

            if ($data['payment_method'] === 'qris') {
                $qrisData = $this->qrisService->generatePayload($transaction->invoice_number, $transaction->total);
                $responseData['qris'] = $qrisData;
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $data['payment_method'] === 'qris' ? 'QRIS Generated' : 'Transaction settled!',
                    ...$responseData,
                ]);
            }

            return redirect()->route('pos.index')->with('success', 'Transaction completed! Invoice: '.$transaction->invoice_number);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withErrors(['error' => 'Failed to process transaction: '.$e->getMessage()]);
        }
    }

    public function checkPaymentStatus($invoice)
    {
        $status = $this->qrisService->checkStatus($invoice);

        if ($status === 'completed') {
            $transaction = \App\Models\Transaction::where('invoice_number', $invoice)->first();
            if ($transaction && $transaction->status !== 'completed') {
                $transaction->update(['status' => 'completed']);
            }

            return response()->json(['success' => true, 'status' => 'completed', 'data' => $transaction->load('details.product')]);
        }

        return response()->json(['success' => true, 'status' => 'pending']);
    }

    public function simulatePaymentSuccess(Request $request)
    {
        $this->qrisService->simulateSuccess($request->invoice);

        return response()->json(['success' => true, 'message' => 'Simulated success for '.$request->invoice]);
    }

    public function customerDisplay()
    {
        return view('pos.display');
    }

    public function getDisplayData()
    {
        $branchId = \Illuminate\Support\Facades\Auth::user()->branch_id;
        $data = cache()->get("display_cart_branch_{$branchId}", [
            'items' => [],
            'subtotal' => 0,
            'total' => 0,
            'status' => 'idle',
        ]);

        return response()->json($data);
    }

    public function syncDisplayData(Request $request)
    {
        $branchId = \Illuminate\Support\Facades\Auth::user()->branch_id;
        $data = [
            'items' => $request->items ?? [],
            'subtotal' => $request->subtotal ?? 0,
            'total' => $request->total ?? 0,
            'status' => $request->status ?? 'active',
        ];

        cache()->put("display_cart_branch_{$branchId}", $data, 30); // 30 seconds TTL

        return response()->json(['success' => true]);
    }
}
