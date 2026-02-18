<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $products = \App\Models\Product::with(['category', 'brand'])
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create(): \Illuminate\View\View
    {
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();
        $branches = \App\Models\Branch::all();

        return view('products.create', compact('categories', 'brands', 'branches'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = \Illuminate\Support\Str::slug($request->name).'-'.time();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        \App\Models\Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(\App\Models\Product $product): \Illuminate\View\View
    {
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();
        $branches = \App\Models\Branch::all();

        return view('products.edit', compact('product', 'categories', 'brands', 'branches'));
    }

    public function update(Request $request, \App\Models\Product $product): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(\App\Models\Product $product): \Illuminate\Http\RedirectResponse
    {
        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
