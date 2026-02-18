<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $stats = [
            'total_sales' => Transaction::whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->sum('total'),
            'transaction_count' => Transaction::whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])->count(),
            'total_items' => \App\Models\TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
            })->sum('quantity'),
            'total_profit' => \App\Models\TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
            })->selectRaw('SUM((price - purchase_price) * quantity) as profit')->value('profit') ?? 0,
        ];

        $recentTransactions = Transaction::with(['user', 'member'])
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->latest()
            ->paginate(20);

        return view('reports.index', compact('stats', 'recentTransactions', 'startDate', 'endDate'));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with(['user', 'member', 'branch'])
            ->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->get();

        $fileName = 'sales_report_'.$startDate.'_to_'.$endDate.'.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Invoice', 'Date', 'Time', 'Branch', 'Cashier', 'Customer', 'Subtotal', 'Tax', 'Discount', 'Total', 'Status'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->created_at->format('Y-m-d'),
                    $t->created_at->format('H:i:s'),
                    $t->branch->name ?? 'N/A',
                    $t->user->name ?? 'N/A',
                    $t->member->name ?? 'Guest',
                    $t->subtotal,
                    $t->tax,
                    $t->discount,
                    $t->total,
                    $t->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
