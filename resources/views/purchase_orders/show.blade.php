@extends('layouts.app')

@section('title', 'PO Details: ' . $purchaseOrder->po_number)
@section('page_title', 'Procurement Analysis')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
 <div class="flex justify-between items-center mb-8">
  <a href="{{ route('purchase-orders.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 flex items-center gap-2 transition-colors">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
   </svg>
   Procurement Logs
  </a>
  <div class="flex gap-4">
   @if($purchaseOrder->status === 'ordered')
   <form action="{{ route('purchase-orders.receive', $purchaseOrder) }}" method="POST">
    @csrf
    <button class="px-8 py-4 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-[1.02] transition-all">Authorize Receipt</button>
   </form>
   @endif
   <button onclick="window.print()" class="px-8 py-4 bg-slate-100 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Print Manifest</button>
  </div>
 </div>

 <div class="premium-card p-10">
  <div class="flex flex-col md:flex-row justify-between gap-10 border-b border-slate-50 pb-10">
   <div class="space-y-4">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Originating Entity</p>
    <div class="flex flex-col">
     <span class="text-xl font-black text-slate-900">{{ config('app.name') }}</span>
     <span class="text-xs font-bold text-slate-500">{{ $purchaseOrder->branch->name }}</span>
    </div>
   </div>
   <div class="space-y-4 text-right">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Partner Entity</p>
    <div class="flex flex-col">
     <span class="text-xl font-black text-indigo-600 uppercase">{{ $purchaseOrder->supplier->name }}</span>
     <span class="text-xs font-bold text-slate-500">{{ $purchaseOrder->supplier->contact_person }}</span>
    </div>
   </div>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-10">
   <div>
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Document Num</p>
    <p class="text-xs font-black uppercase text-slate-900">{{ $purchaseOrder->po_number }}</p>
   </div>
   <div>
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue Date</p>
    <p class="text-xs font-black text-slate-900">{{ $purchaseOrder->order_date->format('d M Y') }}</p>
   </div>
   <div>
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">System Status</p>
    <div class="flex items-center gap-2">
     <div class="w-1.5 h-1.5 rounded-full {{ $purchaseOrder->status === 'received' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
     <p class="text-xs font-black uppercase text-slate-900">{{ $purchaseOrder->status }}</p>
    </div>
   </div>
   <div>
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Authorized By</p>
    <p class="text-xs font-black text-slate-900 uppercase">{{ $purchaseOrder->user->name }}</p>
   </div>
  </div>

  <div class="overflow-x-auto mt-6">
   <table class="w-full text-left border-collapse border border-slate-100 rounded-2xl overflow-hidden">
    <thead>
     <tr class="bg-slate-50">
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Asset</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Unit Cost</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Qty</th>
      <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Line Value</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-700">
     @foreach($purchaseOrder->items as $item)
     <tr>
      <td class="px-6 py-5">
       <span class="uppercase font-black text-slate-900">{{ $item->product->name }}</span>
       <p class="text-[9px] text-slate-400 mt-0.5">{{ $item->product->sku }}</p>
      </td>
      <td class="px-6 py-5 text-center">Rp{{ number_format($item->cost_price, 0, ',', '.') }}</td>
      <td class="px-6 py-5 text-center">{{ $item->quantity }}</td>
      <td class="px-6 py-5 text-right font-black">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
     </tr>
     @endforeach
    </tbody>
    <tfoot>
     <tr class="bg-indigo-600 text-white">
      <td colspan="3" class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.3em] text-right">Total Allocated Asset Value</td>
      <td class="px-6 py-5 text-right text-base font-black">Rp{{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
     </tr>
    </tfoot>
   </table>
  </div>

  @if($purchaseOrder->notes)
  <div class="mt-8 p-6 bg-slate-50 rounded-2xl border border-slate-100">
   <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Administrative Rationale</p>
   <p class="text-xs font-bold text-slate-600 italic">"{{ $purchaseOrder->notes }}"</p>
  </div>
  @endif
 </div>
</div>
@endsection