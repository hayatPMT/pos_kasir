@extends('layouts.app')

@section('title', 'Procurement Management')
@section('page_title', 'Stock Replenishment')

@section('content')
<div class="space-y-8">
 <div class="flex justify-between items-center">
  <div>
   <h3 class="text-2xl font-black text-slate-900 tracking-tight">Purchase Orders</h3>
   <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Global Procurement Flow</p>
  </div>
  <a href="{{ route('purchase-orders.create') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all flex items-center gap-3">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
   </svg>
   Create Purchase Request
  </a>
 </div>

 <div class="premium-card overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead>
     <tr class="bg-slate-50 border-b border-slate-100">
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Document ID</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Partner Entity</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Timeline</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Value Metric</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Activity</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-600">
     @forelse($orders as $order)
     <tr class="hover:bg-indigo-50/30 transition-colors group">
      <td class="px-8 py-5">
       <div class="flex flex-col">
        <span class="text-slate-900 font-black tracking-tight">{{ $order->po_number }}</span>
        <span class="text-[9px] text-slate-400 uppercase mt-0.5">Origin: {{ $order->branch->name }}</span>
       </div>
      </td>
      <td class="px-8 py-5 uppercase font-black text-slate-800">
       {{ $order->supplier->name }}
      </td>
      <td class="px-8 py-5">
       <div class="flex flex-col">
        <span class="text-slate-900 italic font-black">{{ $order->order_date->format('d M Y') }}</span>
        @if($order->status === 'received')
        <span class="text-[9px] text-emerald-500 font-black uppercase tracking-widest mt-1">Validated & Received</span>
        @elseif($order->status === 'ordered')
        <span class="text-[9px] text-amber-500 font-black uppercase tracking-widest mt-1">Awaiting Logistics</span>
        @else
        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">Document Draft</span>
        @endif
       </div>
      </td>
      <td class="px-8 py-5">
       <div class="flex flex-col">
        <span class="text-slate-900 font-black text-sm">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
        <span class="text-[9px] text-slate-400 uppercase mt-0.5">Asset Reinvestment</span>
       </div>
      </td>
      <td class="px-8 py-5 text-right">
       <div class="flex justify-end gap-3 translate-x-4 group-hover:translate-x-0 transition-transform">
        @if($order->status === 'ordered')
        <form action="{{ route('purchase-orders.receive', $order) }}" method="POST">
         @csrf
         <button class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">Receive Stock</button>
        </form>
        @endif
        <a href="{{ route('purchase-orders.show', $order) }}" class="p-2 bg-slate-100 text-slate-500 rounded-lg hover:bg-slate-950 hover:text-white transition-all">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
         </svg>
        </a>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-8 py-20 text-center text-slate-400 uppercase tracking-widest text-[10px] font-black">No procurement cycles records found</td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
 </div>
</div>
@endsection