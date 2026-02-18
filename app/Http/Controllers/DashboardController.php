<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_members' => \App\Models\Member::count(),
            'total_branches' => \App\Models\Branch::count(),
            'recent_transactions' => \App\Models\Transaction::latest()->limit(5)->get(),
            'low_stock' => \App\Models\Product::whereRaw('stock <= min_stock')->limit(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }
}
