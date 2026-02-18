@extends('layouts.app')

@section('title', 'Receipt #' . $transaction->invoice_number)
@section('page_title', 'Transaction Detail')

@section('content')
<div class="max-w-4xl mx-auto">
 <div class="mb-8 flex items-center justify-between">
  <a href="{{ route('transactions.index') }}" class="group flex items-center space-x-3 text-slate-400 hover:text-indigo-600 transition-colors">
   <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-indigo-500/10 transition-all">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
    </svg>
   </div>
   <span class="text-xs font-black uppercase tracking-widest">Back to Ledger</span>
  </a>

  <div class="flex items-center gap-3">
   <button onclick="window.print()" class="px-6 py-3 bg-white border border-slate-100 rounded-xl text-slate-600 hover:text-indigo-600 hover:shadow-xl hover:shadow-indigo-500/10 transition-all flex items-center gap-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
    </svg>
    <span class="text-[10px] font-black uppercase tracking-widest">Print Receipt</span>
   </button>
  </div>
 </div>

 <div class="premium-card !p-0 overflow-hidden bg-white relative">
  <!-- Receipt Header -->
  <div class="p-12 bg-slate-950 text-white relative overflow-hidden">
   <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
   <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-8">
    <div class="space-y-4">
     <div class="inline-flex px-4 py-1.5 bg-indigo-500 rounded-lg text-[10px] font-black uppercase tracking-[0.2em] mb-2">Confirmed Transaction</div>
     <h1 class="text-4xl font-black tracking-tighter">{{ $transaction->invoice_number }}</h1>
     <div class="flex items-center space-x-6">
      <div class="flex flex-col">
       <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Date Issued</span>
       <span class="text-sm font-bold">{{ $transaction->created_at->format('d F Y') }}</span>
      </div>
      <div class="flex flex-col">
       <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Execution Time</span>
       <span class="text-sm font-bold">{{ $transaction->created_at->format('H:i:s') }}</span>
      </div>
      <div class="flex flex-col">
       <span class="text-[9px] font-black text-white/40 uppercase tracking-widest">Branch Location</span>
       <span class="text-sm font-bold">{{ $transaction->branch->name ?? 'Headquarters' }}</span>
      </div>
     </div>
    </div>
    <div class="text-left md:text-right">
     <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-1">Total Settlement</p>
     <p class="text-5xl font-black tracking-tighter text-indigo-400">Rp{{ number_format($transaction->total) }}</p>
    </div>
   </div>
  </div>

  <!-- Participants -->
  <div class="grid grid-cols-1 md:grid-cols-2 border-b border-slate-50">
   <div class="p-10 border-r border-slate-50">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Customer Profile</p>
    @if ($transaction->member)
    <div class="flex items-center space-x-4">
     <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-xl border border-indigo-100">
      {{ substr($transaction->member->name, 0, 1) }}
     </div>
     <div>
      <h4 class="font-black text-slate-900 tracking-tight text-lg">{{ $transaction->member->name }}</h4>
      <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $transaction->member->member_code }}</p>
     </div>
    </div>
    @else
    <div class="flex items-center space-x-4 opacity-50">
     <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center font-black text-xl border border-slate-100">
      G
     </div>
     <div>
      <h4 class="font-black text-slate-900 tracking-tight text-lg">Guest Customer</h4>
      <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-0.5">Non-Member Transaction</p>
     </div>
    </div>
    @endif
   </div>
   <div class="p-10">
    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Authorized By</p>
    <div class="flex items-center space-x-4">
     <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-sm">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
      </svg>
     </div>
     <div>
      <h4 class="font-black text-slate-900 tracking-tight text-lg">{{ $transaction->user->name }}</h4>
      <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-0.5">System Operator</p>
     </div>
    </div>
   </div>
  </div>

  <!-- Items Table -->
  <div class="p-10">
   <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Purchase Manifest</p>
   <table class="w-full">
    <thead>
     <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100 pb-4">
      <th class="text-left pb-4">Item Catalog</th>
      <th class="text-center pb-4">Quantity</th>
      <th class="text-right pb-4">Unit Value</th>
      <th class="text-right pb-4">Extended Total</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
     @foreach ($transaction->details as $detail)
     <tr class="group">
      <td class="py-6">
       <div class="flex items-center space-x-4">
        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-[10px] font-black text-slate-300 border border-slate-100 group-hover:bg-indigo-50 group-hover:text-indigo-400 transition-colors">
         {{ $loop->iteration }}
        </div>
        <div class="min-w-0">
         <p class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tight">{{ $detail->product->name }}</p>
         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-0.5">SKU: {{ $detail->product->sku }}</p>
        </div>
       </div>
      </td>
      <td class="py-6 text-center font-black text-slate-900">{{ $detail->quantity }}</td>
      <td class="py-6 text-right font-bold text-slate-600">Rp{{ number_format($detail->price) }}</td>
      <td class="py-6 text-right font-black text-slate-900 text-lg tracking-tight">Rp{{ number_format($detail->total) }}</td>
     </tr>
     @endforeach
    </tbody>
   </table>
  </div>

  <!-- Totals & Payment -->
  <div class="bg-slate-50/50 p-10 mt-6 flex flex-col md:flex-row justify-between items-start gap-12">
   <div class="flex-1 space-y-6">
    <div>
     <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Internal Notes</p>
     <div class="p-6 bg-white rounded-2xl border border-slate-100 text-xs text-slate-500 italic leading-relaxed shadow-inner">
      {{ $transaction->notes ?? 'No special instructions or comments provided for this transaction.' }}
     </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
     <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
      <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Entry Method</p>
      <p class="text-sm font-black text-slate-900">Direct Terminal Entry</p>
     </div>
     <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
      <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Promotion Applied</p>
      <p class="text-sm font-black text-indigo-600">{{ $transaction->promotion->name ?? 'None' }}</p>
     </div>
    </div>
   </div>

   <div class="w-full md:w-80 space-y-4">
    <div class="flex justify-between items-center text-slate-500">
     <span class="text-xs font-bold uppercase tracking-widest">Gross Subtotal</span>
     <span class="font-black">Rp{{ number_format($transaction->subtotal) }}</span>
    </div>
    <div class="flex justify-between items-center text-rose-500">
     <span class="text-xs font-bold uppercase tracking-widest">Campaign Adjustment</span>
     <span class="font-black">-Rp{{ number_format($transaction->discount) }}</span>
    </div>
    <div class="flex justify-between items-center text-slate-500">
     <span class="text-xs font-bold uppercase tracking-widest">Tax Provision</span>
     <span class="font-black">Rp{{ number_format($transaction->tax) }}</span>
    </div>
    <div class="pt-6 border-t border-slate-200">
     <div class="flex justify-between items-center mb-2">
      <span class="text-sm font-black text-slate-900 uppercase tracking-[0.1em]">Total Final</span>
      <span class="text-3xl font-black text-indigo-600 tracking-tighter">Rp{{ number_format($transaction->total) }}</span>
     </div>
     <div class="flex justify-between items-center text-slate-400">
      <span class="text-[10px] font-bold uppercase tracking-widest">Payment Received</span>
      <span class="text-sm font-bold">Rp{{ number_format($transaction->pay_amount) }}</span>
     </div>
     <div class="flex justify-between items-center text-emerald-500 mt-2 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
      <span class="text-[10px] font-black uppercase tracking-widest">Change Refunded</span>
      <span class="text-lg font-black">Rp{{ number_format($transaction->change_amount) }}</span>
     </div>
    </div>
   </div>
  </div>

  <!-- Receipt Teeth -->
  <div class="h-2 bg-slate-50 w-full relative">
   <div class="absolute inset-0 flex">
    @for($i=0; $i<40; $i++)
     <div class="flex-1 bg-white" style="clip-path: polygon(50% 100%, 0 0, 100% 0);">
   </div>
   @endfor
   <div class="flex-1 bg-white" style="clip-path: polygon(50% 100%, 0 0, 100% 0);"></div>
   @endfor
  </div>
 </div>
</div>
</div>

<style>
 @media print {
  body * {
   visibility: hidden;
  }

  .premium-card,
  .premium-card * {
   visibility: visible;
  }

  .premium-card {
   position: absolute;
   left: 0;
   top: 0;
   width: 100%;
   border: none !important;
   box-shadow: none !important;
  }

  nav,
  aside,
  header,
  footer,
  .mb-8 {
   display: none !important;
  }
 }
</style>
@endsection