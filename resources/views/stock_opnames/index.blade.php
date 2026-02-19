@extends('layouts.app')

@section('title', 'Inventory Audits')
@section('page_title', 'Stock Verification')

@section('content')
<div class="space-y-8">
 <div class="flex justify-between items-center">
  <div>
   <h3 class="text-2xl font-black text-slate-900 tracking-tight">Stock Opname Logs</h3>
   <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Operational Audit Trail</p>
  </div>
  <a href="{{ route('stock-opnames.create') }}" class="px-8 py-4 bg-rose-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-rose-600/20 hover:bg-rose-700 transition-all flex items-center gap-3">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
   </svg>
   Initiate Physical Audit
  </a>
 </div>

 <div class="premium-card overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead>
     <tr class="bg-slate-50 border-b border-slate-100">
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Audit Reference</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Auditor</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Timestamp</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Utility</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-600">
     @forelse($opnames as $op)
     <tr class="hover:bg-slate-50/50 transition-colors group">
      <td class="px-8 py-5">
       <div class="flex flex-col">
        <span class="text-slate-900 font-black tracking-tight">{{ $op->reference_number }}</span>
        <span class="text-[9px] text-slate-400 uppercase mt-0.5">{{ $op->branch->name }} Node</span>
       </div>
      </td>
      <td class="px-8 py-5">
       <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-[10px]">
         {{ substr($op->user->name, 0, 1) }}
        </div>
        <span class="uppercase">{{ $op->user->name }}</span>
       </div>
      </td>
      <td class="px-8 py-5 text-slate-500 italic">
       {{ $op->date->format('d M Y') }}
      </td>
      <td class="px-8 py-5">
       @if($op->status === 'completed')
       <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">Audit Applied</span>
       @else
       <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-[9px] font-black uppercase tracking-widest">Draft Verification</span>
       @endif
      </td>
      <td class="px-8 py-5 text-right">
       <div class="flex justify-end gap-3 transition-transform">
        @if($op->status === 'draft')
        <form action="{{ route('stock-opnames.complete', $op) }}" method="POST">
         @csrf
         <button class="px-4 py-2 bg-slate-950 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg hover:bg-slate-800 transition-all">Apply Adjustment</button>
        </form>
        @endif
        <a href="{{ route('stock-opnames.show', $op) }}" class="p-2 bg-slate-100 text-slate-500 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">
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
      <td colspan="5" class="px-8 py-20 text-center text-slate-400 uppercase tracking-widest text-[10px] font-black">No physical audits performed in this node</td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
 </div>
</div>
@endsection