@extends('layouts.app')

@section('title', 'Financial Reporting')
@section('page_title', 'Strategic Sales Reports')

@section('content')
<div class="space-y-8">
 <!-- Filter Bar -->
 <div class="premium-card p-6 bg-white/50 backdrop-blur-md">
  <form action="{{ route('reports.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-6">
   <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
    <div class="flex flex-col">
     <label class="text-[10px] uppercase font-black text-slate-400 mb-2 tracking-[0.2em]">Start Period</label>
     <input type="date" name="start_date" value="{{ $startDate }}" class="input-premium">
    </div>
    <div class="flex flex-col">
     <label class="text-[10px] uppercase font-black text-slate-400 mb-2 tracking-[0.2em]">End Period</label>
     <input type="date" name="end_date" value="{{ $endDate }}" class="input-premium">
    </div>
   </div>
   <div class="flex gap-3 w-full md:w-auto">
    <button type="submit" class="btn-primary flex-1 md:flex-none py-3.5 px-8">Generate Analysis</button>
    <a href="{{ route('reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
     class="btn-secondary flex-1 md:flex-none py-3.5 px-8 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border-emerald-200 border">
     Export CSV
    </a>
   </div>
  </form>
 </div>

 <!-- Summary Stats -->
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
  <div class="premium-card p-8 bg-indigo-600 text-white shadow-2xl shadow-indigo-600/20">
   <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-4">Total Revenue</p>
   <h3 class="text-3xl font-black tracking-tight">Rp{{ number_format($stats['total_sales']) }}</h3>
  </div>
  <div class="premium-card p-8 bg-emerald-600 text-white shadow-2xl shadow-emerald-600/20">
   <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-4">Net Profit Margin</p>
   <h3 class="text-3xl font-black tracking-tight">Rp{{ number_format($stats['total_profit']) }}</h3>
  </div>
  <div class="premium-card p-8 bg-white">
   <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Transactions</p>
   <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['transaction_count']) }}</h3>
  </div>
  <div class="premium-card p-8 bg-white">
   <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Units Sold</p>
   <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_items']) }}</h3>
  </div>
 </div>

 <!-- Data Table -->
 <div class="premium-card !p-0 overflow-hidden">
  <div class="p-8 border-b border-slate-50 flex items-center justify-between">
   <h3 class="text-xl font-black text-slate-900 tracking-tight">Detailed Audit Trail</h3>
   <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">{{ $startDate }} // {{ $endDate }}</span>
  </div>
  <div class="overflow-x-auto text-sm">
   <table class="w-full text-left">
    <thead>
     <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-50">
      <th class="px-8 py-5">Invoice</th>
      <th class="px-8 py-5">Cashier</th>
      <th class="px-8 py-5">Customer</th>
      <th class="px-8 py-5 text-right">Settlement</th>
      <th class="px-8 py-5 text-center">Timestamp</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
     @forelse($recentTransactions as $t)
     <tr class="hover:bg-slate-50/50 transition-colors group">
      <td class="px-8 py-6">
       <span class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $t->invoice_number }}</span>
      </td>
      <td class="px-8 py-6">
       <span class="font-bold text-slate-600">{{ $t->user->name ?? 'System' }}</span>
      </td>
      <td class="px-8 py-6">
       <span class="font-bold text-slate-500 uppercase text-[10px] tracking-widest">{{ $t->member->name ?? 'Guest' }}</span>
      </td>
      <td class="px-8 py-6 text-right">
       <span class="font-black text-slate-900">Rp{{ number_format($t->total) }}</span>
      </td>
      <td class="px-8 py-6 text-center text-[10px] font-bold text-slate-400 uppercase">
       {{ $t->created_at->format('d M Y') }}<br>{{ $t->created_at->format('H:i') }}
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-8 py-20 text-center opacity-40">
       <p class="font-black uppercase tracking-widest text-xs">No records found for this period</p>
      </td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($recentTransactions->hasPages())
  <div class="p-8 border-t border-slate-50">
   {{ $recentTransactions->links() }}
  </div>
  @endif
 </div>
</div>
@endsection