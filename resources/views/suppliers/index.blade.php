@extends('layouts.app')

@section('title', 'Suppliers Management')
@section('page_title', 'Strategic Partners')

@section('content')
<div class="space-y-8">
 <div class="flex justify-between items-center">
  <div>
   <h3 class="text-2xl font-black text-slate-900 tracking-tight">Supplier Directory</h3>
   <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Total Active Partners: {{ $suppliers->total() }}</p>
  </div>
  <a href="{{ route('suppliers.create') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all flex items-center gap-3">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
   </svg>
   Onboard New Supplier
  </a>
 </div>

 <div class="premium-card overflow-hidden">
  <div class="overflow-x-auto">
   <table class="w-full text-left border-collapse">
    <thead>
     <tr class="bg-slate-50 border-b border-slate-100">
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Supplier Entity</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact Point</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reach</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Utility</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-600">
     @forelse($suppliers as $supplier)
     <tr class="hover:bg-indigo-50/30 transition-colors group">
      <td class="px-8 py-5">
       <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors font-black">
         {{ substr($supplier->name, 0, 1) }}
        </div>
        <div class="flex flex-col">
         <span class="text-slate-900 uppercase font-black tracking-tight">{{ $supplier->name }}</span>
         <span class="text-[9px] text-slate-400 uppercase mt-0.5">{{ $supplier->address ?: 'No Address provided' }}</span>
        </div>
       </div>
      </td>
      <td class="px-8 py-5">
       <div class="flex flex-col">
        <span class="text-slate-900 uppercase">{{ $supplier->contact_person ?: '-' }}</span>
        <span class="text-[9px] text-slate-400 uppercase mt-0.5">Manager</span>
       </div>
      </td>
      <td class="px-8 py-5 space-y-1">
       <div class="flex items-center gap-2 opacity-60">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        <span>{{ $supplier->email ?: '-' }}</span>
       </div>
       <div class="flex items-center gap-2 opacity-60">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
        </svg>
        <span>{{ $supplier->phone ?: '-' }}</span>
       </div>
      </td>
      <td class="px-8 py-5">
       @if($supplier->is_active)
       <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-widest">Active Partner</span>
       @else
       <span class="px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest">Inactive</span>
       @endif
      </td>
      <td class="px-8 py-5 text-right">
       <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
         </svg>
        </a>
        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Archive this partner?')">
         @csrf @method('DELETE')
         <button class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
         </button>
        </form>
       </div>
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-8 py-20 text-center text-slate-400 uppercase tracking-widest text-[10px] font-black">No partners registered in node directory</td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>
  @if($suppliers->hasPages())
  <div class="px-8 py-5 bg-slate-50 border-t border-slate-100">
   {{ $suppliers->links() }}
  </div>
  @endif
 </div>
</div>
@endsection