<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product', 'payments']);

        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'user_id' => 'required|exists:users,id',
                'member_id' => 'nullable|exists:members,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'nullable|numeric|min:0',
                'pay_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,transfer,qris,e-wallet,debit,credit',
                'payment_reference' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $transaction = $this->transactionService->createTransaction($validated);

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load(['details.product', 'payments', 'user', 'member']));
    }
}
