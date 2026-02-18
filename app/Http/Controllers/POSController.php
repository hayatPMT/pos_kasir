<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class POSController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request): \Illuminate\View\View
    {
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

    public function store(Request $request)
    {
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

            $transaction = $this->transactionService->createTransaction($data);
            $transaction->load(['details.product', 'member', 'user', 'branch']);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction settled!',
                    'data' => $transaction
                ]);
            }

            return redirect()->route('pos.index')->with('success', 'Transaction completed! Invoice: ' . $transaction->invoice_number);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            }
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Failed to process transaction: ' . $e->getMessage()]);
        }
    }
}
