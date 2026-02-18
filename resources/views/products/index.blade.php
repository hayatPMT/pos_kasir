@extends('layouts.app')

@section('title', 'Inventory')
@section('page_title', 'Product Catalog')

@section('content')
<div class="premium-card overflow-hidden !shadow-none border-none">
  <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex flex-col">
      <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Active Inventory</h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Manage and monitor your product stock</p>
    </div>

    <div class="flex items-center gap-4">
      <form action="{{ route('products.index') }}" method="GET" class="relative group">
        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}"
          class="w-full md:w-80 pl-12 pr-6 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold placeholder-slate-300 shadow-inner"
          placeholder="Search by SKU or name...">
      </form>

      <a href="{{ route('products.create') }}"
        class="btn-primary group">
        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <span class="text-xs uppercase tracking-widest">Add Product</span>
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-b border-slate-50 bg-slate-50/30">
          <th class="px-10 py-5">Product Assets</th>
          <th class="px-10 py-5">Financial Details</th>
          <th class="px-10 py-5">Inventory Status</th>
          <th class="px-10 py-5">Availability</th>
          <th class="px-10 py-5 text-right">Operational</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        @forelse ($products as $product)
        <tr class="hover:bg-slate-50/50 transition-colors group">
          <td class="px-10 py-6">
            <div class="flex items-center space-x-5">
              <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden border border-slate-200/50 shadow-sm relative group-hover:scale-105 transition-transform">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center bg-slate-50">
                  <svg class="w-6 h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                </div>
                @endif
                <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/5 transition-colors"></div>
              </div>
              <div class="min-w-0">
                <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate tracking-tight">{{ $product->name }}</h4>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-0.5">{{ $product->sku }}</p>
                <p class="text-[9px] font-bold text-slate-400 mt-1 italic">{{ $product->category->name }}</p>
              </div>
            </div>
          </td>
          <td class="px-10 py-6">
            <div class="space-y-1">
              <p class="text-sm font-black text-slate-900 block tracking-tight">Rp{{ number_format($product->sell_price) }}</p>
              <p class="text-[10px] font-bold text-slate-400 uppercase">Cost: Rp{{ number_format($product->buy_price) }}</p>
            </div>
          </td>
          <td class="px-10 py-6">
            <div class="flex flex-col space-y-2">
              <div class="flex items-center space-x-2">
                <span class="text-lg font-black text-slate-900">{{ $product->stock }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Units</span>
              </div>
              @if ($product->stock <= $product->min_stock)
                <span class="inline-flex px-2 py-0.5 bg-rose-50 text-rose-500 text-[8px] font-black uppercase rounded-md border border-rose-100 animate-pulse w-fit">Restock Needed</span>
                @endif
            </div>
          </td>
          <td class="px-10 py-6">
            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm {{ $product->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
              {{ $product->is_active ? 'In Catalog' : 'Hidden' }}
            </span>
          </td>
          <td class="px-10 py-6 text-right">
            <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
              <a href="{{ route('products.edit', $product->id) }}" class="p-3 text-slate-400 hover:text-indigo-600 bg-white hover:shadow-xl hover:shadow-indigo-500/10 rounded-2xl border border-slate-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </a>
              <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Remove product from inventory?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-3 text-slate-400 hover:text-rose-600 bg-white hover:shadow-xl hover:shadow-rose-500/10 rounded-2xl border border-slate-100 transition-all">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-10 py-32 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-slate-50/20 backdrop-blur-sm -z-10"></div>
            <div class="flex flex-col items-center">
              <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex items-center justify-center mb-10 group hover:scale-110 transition-transform duration-700">
                <svg class="w-14 h-14 text-slate-100 group-hover:text-indigo-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Inventory empty</h3>
              <p class="text-slate-400 max-w-sm mx-auto mb-10 font-medium">Your catalog is ready for products. Start by adding items to your stock registry.</p>
              <a href="{{ route('products.create') }}" class="btn-primary !px-12 !py-5 !rounded-[2rem]">Import Stock</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($products->hasPages())
  <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
    {{ $products->links() }}
  </div>
  @endif
</div>
@endsection