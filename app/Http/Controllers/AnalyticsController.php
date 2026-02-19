<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __invoke()
    {
        // 1. Best Selling Products (Top 5)
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        // 2. Deadstock Analysis (No sales in 30 days)
        $soldProductIds = TransactionDetail::whereHas('transaction', function ($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        })->distinct()->pluck('product_id');

        $deadstock = Product::whereNotIn('id', $soldProductIds)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'desc')
            ->limit(5)
            ->get();

        // 3. Hourly Sales Heatmap (Last 7 days)
        $hourlySales = Transaction::select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Ensure all 24 hours are represented
        $heatmap = [];
        for ($i = 0; $i < 24; $i++) {
            $heatmap[$i] = $hourlySales[$i] ?? 0;
        }

        // 4. Staff Performance
        $staffPerformance = Transaction::select('user_id', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as total_sales'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('total_sales', 'desc')
            ->get();

        return view('analytics.index', compact('topProducts', 'deadstock', 'heatmap', 'staffPerformance'));
    }
}
