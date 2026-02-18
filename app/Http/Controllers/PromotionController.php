<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Promotion::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $promotions = $query->latest()->paginate(10);

        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,nominal,buy_get',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        \App\Models\Promotion::create($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function edit(\App\Models\Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    public function update(Request $request, \App\Models\Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,nominal,buy_get',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($request->all());

        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(\App\Models\Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully.');
    }
}
