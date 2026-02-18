@extends('layouts.app')

@section('title', 'Sales History')
@section('page_title', 'Transaction Registry')

@section('content')
<div class="premium-card overflow-hidden !shadow-none border-none">
 <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
  <div class="flex flex-col">
   <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Financial Records</h3>
   <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Audit trail for all business transactions</p>
  </div>

  <div class="flex items-center gap-4">
   <form action="{{ route('transactions.index') }}" method="GET" class="relative group">
    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
     </svg>
    </span>
    <input type="text" name="search" value="{{ request('search') }}"
     class="w-full md:w-80 pl-12 pr-6 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold placeholder-slate-300 shadow-inner"
     placeholder="Search invoice number...">
   </form>

   <button onclick="window.print()"
    class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-slate-900/10 transition-all active:scale-95 flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
    </svg>
    <span>Print Ledger</span>
   </button>
  </div>
 </div>

 <div class="overflow-x-auto">
  <table class="w-full text-left border-collapse">
   <thead>
    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-b border-slate-50 bg-slate-50/30">
     <th class="px-10 py-5">Ledger Entry</th>
     <th class="px-10 py-5">Purchaser Details</th>
     <th class="px-10 py-5">Settlement</th>
     <th class="px-10 py-5">Verification</th>
     <th class="px-10 py-5 text-right">Discovery</th>
    </tr>
   </thead>
   <tbody class="divide-y divide-slate-50">
    @forelse ($transactions as $transaction)
    <tr class="hover:bg-slate-50/50 transition-colors group">
     <td class="px-10 py-6">
      <div class="flex items-center space-x-5">
       <div class="w-12 h-12 bg-slate-950 text-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-950/20 group-hover:scale-110 transition-transform">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
       </div>
       <div class="min-w-0">
        <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tighter">{{ $transaction->invoice_number }}</h4>
        <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">{{ $transaction->created_at->format('d M Y | H:i') }}</p>
       </div>
      </div>
     </td>
     <td class="px-10 py-6">
      <div class="flex flex-col">
       <span class="text-xs font-black text-slate-700 tracking-tight">{{ $transaction->member->name ?? 'Guest User' }}</span>
       <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Payment via {{ ucfirst($transaction->payments->first()->method ?? 'Unspecified') }}</span>
      </div>
     </td>
     <td class="px-10 py-6">
      <div class="flex flex-col">
       <span class="text-lg font-black text-slate-900 tracking-tighter">Rp{{ number_format($transaction->total) }}</span>
       @if ($transaction->discount > 0)
       <span class="text-[9px] font-bold text-rose-500 uppercase">Saved Rp{{ number_format($transaction->discount) }}</span>
       @endif
      </div>
     </td>
     <td class="px-10 py-6">
      <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm {{ $transaction->status == 'completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
       {{ $transaction->status }}
      </span>
     </td>
     <td class="px-10 py-6 text-right">
      <a href="{{ route('transactions.show', $transaction->id) }}"
       class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white text-slate-500 hover:text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all">
       <span>Examine</span>
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
       </svg>
      </a>
     </td>
    </tr>
    @empty
    <tr>
     <td colspan="5" class="px-10 py-32 text-center relative overflow-hidden">
      <div class="absolute inset-0 bg-slate-50/20 backdrop-blur-sm -z-10"></div>
      <div class="flex flex-col items-center">
       <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex items-center justify-center mb-10 group hover:scale-110 transition-transform duration-700">
        <svg class="w-14 h-14 text-slate-100 group-hover:text-indigo-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
       </div>
       <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Financial ledger empty</h3>
       <p class="text-slate-400 max-w-sm mx-auto mb-10 font-medium">Capture your first sale to start generating records. Head over to the POS terminal to begin.</p>
       <a href="{{ route('pos.index') }}" class="btn-primary !px-12 !py-5 !rounded-[2rem]">Open Terminal</a>
      </div>
     </td>
    </tr>
    @endforelse
   </tbody>
  </table>
 </div>

 @if ($transactions->hasPages())
 <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
  {{ $transactions->links() }}
 </div>
 @endif
</div>
@endsection