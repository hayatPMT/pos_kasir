@extends('layouts.app')

@section('title', 'Audit Analysis: ' . $stockOpname->reference_number)
@section('page_title', 'Inventory Reconciliation')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
 <div class="flex justify-between items-center mb-8">
  <a href="{{ route('stock-opnames.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 flex items-center gap-2 transition-colors">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
   </svg>
   Audit Logs
  </a>
  <div class="flex gap-4">
   @if($stockOpname->status === 'draft')
   <form action="{{ route('stock-opnames.complete', $stockOpname) }}" method="POST">
    @csrf
    <button class="px-8 py-4 bg-slate-950 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl hover:bg-slate-800 transition-all">Authorize Adjustment</button>
   </form>
   @endif
   <button onclick="window.print()" class="px-8 py-4 bg-slate-100 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Export Audit Report</button>
  </div>
 </div>

 <div class="premium-card p-10">
  <div class="flex flex-col md:flex-row justify-between gap-10 border-b border-slate-50 pb-10">
   <div class="space-y-4">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Audit Scope</p>
    <div class="flex flex-col">
     <span class="text-xl font-black text-slate-900 uppercase">{{ $stockOpname->reference_number }}</span>
     <span class="text-xs font-bold text-slate-500">{{ $stockOpname->branch->name }} Node</span>
    </div>
   </div>
   <div class="space-y-4 text-right">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Operational Status</p>
    <div class="flex flex-col items-end">
     @if($stockOpname->status === 'completed')
     <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">Audit Applied</span>
     @else
     <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-lg text-[9px] font-black uppercase tracking-widest text-right">Draft Verification</span>
     @endif
     <span class="text-xs font-bold text-slate-500 mt-2">Verifier: {{ $stockOpname->user->name }}</span>
    </div>
   </div>
  </div>

  <div class="overflow-x-auto mt-10">
   <table class="w-full text-left border-collapse border border-slate-100 rounded-2xl overflow-hidden">
    <thead>
     <tr class="bg-slate-50">
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Asset</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">System Logic</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Physical Count</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Variance</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-700">
     @foreach($stockOpname->details as $detail)
     <tr>
      <td class="px-6 py-5">
       <span class="uppercase font-black text-slate-900">{{ $detail->product->name }}</span>
       <p class="text-[9px] text-slate-400 mt-0.5">{{ $detail->product->sku }}</p>
      </td>
      <td class="px-6 py-5 text-center text-slate-400">{{ $detail->system_stock }}</td>
      <td class="px-6 py-5 text-center font-black text-slate-900">{{ $detail->physical_stock }}</td>
      <td class="px-6 py-5 text-center">
       @if($detail->difference > 0)
       <span class="text-emerald-500 font-black">+{{ $detail->difference }}</span>
       @elseif($detail->difference < 0)
        <span class="text-rose-500 font-black">{{ $detail->difference }}</span>
        @else
        <span class="text-slate-300 font-black">0</span>
        @endif
      </td>
     </tr>
     @endforeach
    </tbody>
   </table>
  </div>

  @if($stockOpname->notes)
  <div class="mt-10 p-8 bg-slate-950 text-white rounded-[2.5rem] relative overflow-hidden">
   <div class="absolute top-0 right-0 p-10 opacity-10">
    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
     <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017V14H14.017V6H20.017V16H17.017C17.017 16.5523 16.5693 17 16.017 17H14.017V21H14.017ZM4 21V17H6C6.55228 17 7 16.5523 7 16H4V6H10V16C10 17.1046 9.10457 18 8 18H7V21H4Z"></path>
    </svg>
   </div>
   <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-4 relative z-10">Auditor Observations</p>
   <p class="text-sm font-bold italic relative z-10">{{ $stockOpname->notes }}</p>
  </div>
  @endif
 </div>
</div>
@endsection