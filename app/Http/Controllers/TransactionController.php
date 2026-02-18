<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $search = $request->query('search');

        $transactions = Transaction::with(['member', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->where('invoice_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction): \Illuminate\View\View
    {
        $transaction->load(['details.product', 'member', 'user', 'branch']);

        return view('transactions.show', compact('transaction'));
    }
}
