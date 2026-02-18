@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
  <!-- Total Products -->
  <div class="premium-card p-8 flex items-center space-x-6 group">
    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm border border-indigo-100/50">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
      </svg>
    </div>
    <div>
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Inventory</p>
      <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_products']) }}</h3>
    </div>
  </div>

  <!-- Total Members -->
  <div class="premium-card p-8 flex items-center space-x-6 group">
    <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 shadow-sm border border-rose-100/50">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
      </svg>
    </div>
    <div>
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Customers</p>
      <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_members']) }}</h3>
    </div>
  </div>

  <!-- Total Branches -->
  <div class="premium-card p-8 flex items-center space-x-6 group">
    <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-sm border border-amber-100/50">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
      </svg>
    </div>
    <div>
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Branches</p>
      <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_branches']) }}</h3>
    </div>
  </div>

  <!-- Target Performance -->
  <div class="bg-indigo-600 p-8 rounded-[2rem] shadow-2xl shadow-indigo-600/30 text-white flex items-center space-x-6 relative overflow-hidden group hover:scale-[1.03] transition-transform duration-500">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
      </svg>
    </div>
    <div class="relative z-10">
      <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-1">Growth</p>
      <h3 class="text-3xl font-black tracking-tight">+12.5%</h3>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Recent Transactions -->
  <div class="lg:col-span-2 premium-card !shadow-none border-none overflow-hidden">
    <div class="px-10 py-8 flex items-center justify-between">
      <div class="flex flex-col">
        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Recent Activity</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Last 5 Transactions</p>
      </div>
      <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 rounded-xl text-xs font-black uppercase tracking-widest transition-all">Export Report</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left">
        <thead>
          <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
            <th class="px-10 py-5">Invoice</th>
            <th class="px-a py-5">Status</th>
            <th class="px-10 py-5">Amount</th>
            <th class="px-10 py-5">Timestamp</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          @forelse ($stats['recent_transactions'] as $transaction)
          <tr class="hover:bg-slate-50/50 transition-colors group">
            <td class="px-10 py-6">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 rounded-full bg-indigo-500 group-hover:scale-150 transition-transform"></div>
                <span class="font-bold text-slate-900 tracking-tight">{{ $transaction->invoice_number }}</span>
              </div>
            </td>
            <td class="px-10 py-6">
              <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border
                                {{ $transaction->status == 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                {{ $transaction->status }}
              </span>
            </td>
            <td class="px-10 py-6">
              <p class="font-black text-slate-900 tracking-tight text-lg">Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
            </td>
            <td class="px-10 py-6">
              <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-700">{{ $transaction->created_at->format('d M Y') }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $transaction->created_at->format('H:i') }}</span>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-10 py-20 text-center">
              <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                  <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No activity recorded today</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Quick Tools/Alert -->
  <div class="space-y-8">
    <div class="bg-indigo-950 rounded-[2rem] p-10 text-white shadow-2xl shadow-indigo-900/40 relative overflow-hidden group">
      <div class="absolute -top-20 -right-20 w-60 h-60 bg-indigo-600/20 rounded-full blur-[80px] group-hover:scale-150 transition-transform duration-1000"></div>
      <div class="relative z-10">
        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-8 border border-white/10">
          <svg class="w-8 h-8 text-white text-white shadow-xl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
        </div>
        <h4 class="text-2xl font-black mb-3 tracking-tight">Ready to Sell?</h4>
        <p class="text-indigo-200 text-sm mb-10 leading-relaxed font-medium">Launch the smart terminal to handle customer orders and instant payments.</p>
        <a href="{{ route('pos.index') }}" class="w-full inline-flex items-center justify-center space-x-3 bg-white text-indigo-950 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-slate-50 hover:-translate-y-1 transition-all active:scale-95">
          <span>Open POS Terminal</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="premium-card p-10">
      <div class="flex items-center justify-between mb-8">
        <div class="flex flex-col">
          <h4 class="text-lg font-extrabold text-slate-900 tracking-tight">Stock Warnings</h4>
          <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mt-1">Low Inventory Alert</p>
        </div>
        <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center animate-pulse">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
          </svg>
        </div>
      </div>
      <div class="space-y-5">
        @forelse ($stats['low_stock'] as $p)
        <div class="flex items-center justify-between group p-3 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100">
          <div class="flex items-center space-x-4">
            <div class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-500/40"></div>
            <div class="flex flex-col">
              <span class="text-sm font-bold text-slate-700 group-hover:text-rose-700 transition-colors">{{ $p->name }}</span>
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter italic">SKU: {{ $p->sku }}</span>
            </div>
          </div>
          <span class="text-xs font-black text-rose-500 bg-rose-100/50 px-3 py-1 rounded-lg">{{ $p->stock }} LEFT</span>
        </div>
        @empty
        <div class="py-10 text-center">
          <p class="text-slate-400 text-xs font-bold uppercase tracking-widest italic opacity-50">Stock levels healthy</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection