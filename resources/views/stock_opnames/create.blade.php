@extends('layouts.app')

@section('title', 'Initiate Physical Audit')
@section('page_title', 'Inventory Verification')

@section('content')
<div class="max-w-6xl mx-auto">
 <form action="{{ route('stock-opnames.store') }}" method="POST" class="space-y-8">
  @csrf
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
   <div>
    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Audit Worksheet</h3>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Comparing System Logic vs Physical Reality</p>
   </div>
   <div class="flex gap-4">
    <div class="space-y-1">
     <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Audit Date</label>
     <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="px-6 py-3 rounded-2xl border border-slate-100 bg-white font-bold text-xs outline-none focus:ring-4 focus:ring-indigo-500/10">
    </div>
    <button type="submit" class="px-8 py-4 bg-slate-950 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl hover:bg-slate-800 transition-all flex items-center gap-3 self-end">
     Save Audit Draft
    </button>
   </div>
  </div>

  <div class="premium-card p-6 bg-amber-50 border-amber-100">
   <div class="flex items-start gap-4">
    <div class="p-2 bg-amber-200 text-amber-700 rounded-lg">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
     </svg>
    </div>
    <div>
     <h5 class="text-xs font-black text-amber-900 uppercase tracking-tight">Audit Protocol</h5>
     <p class="text-[10px] text-amber-700/70 font-bold uppercase tracking-widest mt-0.5">Physical counts should be performed while the terminal is inactive to prevent logic drift.</p>
    </div>
   </div>
  </div>

  <div class="premium-card overflow-hidden">
   <table class="w-full text-left border-collapse">
    <thead>
     <tr class="bg-slate-50 border-b border-slate-100">
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asset Details</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">System Logic</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Physical Reality</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Variance</th>
      <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reason / Notes</th>
     </tr>
    </thead>
    <tbody class="divide-y divide-slate-50 text-xs font-bold text-slate-600">
     @forelse($products as $idx => $p)
     <tr class="hover:bg-slate-50/50 transition-colors">
      <td class="px-8 py-5">
       <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $p->id }}">
       <div class="flex flex-col">
        <span class="text-slate-900 font-black tracking-tight uppercase">{{ $p->name }}</span>
        <span class="text-[9px] text-slate-400 uppercase mt-0.5">SKU: {{ $p->sku }}</span>
       </div>
      </td>
      <td class="px-8 py-5 text-center text-slate-400 font-black">
       <span class="system-stock">{{ $p->stock }}</span>
      </td>
      <td class="px-8 py-5 text-center">
       <input type="number" name="items[{{ $idx }}][physical_stock]" value="{{ $p->stock }}" min="0" required oninput="calculateVariance(this)"
        class="w-24 px-3 py-2 text-center rounded-xl border border-slate-100 bg-white font-black text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
      </td>
      <td class="px-8 py-5 text-center">
       <span class="variance-badge px-3 py-1 bg-slate-100 text-slate-400 rounded-lg font-black text-[10px]">0</span>
      </td>
      <td class="px-8 py-5">
       <input type="text" name="items[{{ $idx }}][reason]" class="w-full px-4 py-2 bg-white border border-slate-100 rounded-xl outline-none text-[10px] font-bold placeholder:opacity-40" placeholder="e.g. Broken packaging">
      </td>
     </tr>
     @empty
     <tr>
      <td colspan="5" class="px-8 py-20 text-center text-slate-400 uppercase tracking-widest text-[10px] font-black">No assets found in current node for auditing</td>
     </tr>
     @endforelse
    </tbody>
   </table>
  </div>

  <div class="premium-card p-10 mt-8">
   <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1 mb-4 block">Final Audit Remarks</label>
   <textarea name="notes" rows="4" class="w-full px-6 py-4 rounded-3xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-700 outline-none" placeholder="General observation from physical audit..."></textarea>
  </div>
 </form>
</div>

<script>
 function calculateVariance(input) {
  const row = input.closest('tr');
  const systemStock = parseInt(row.querySelector('.system-stock').innerText);
  const physicalStock = parseInt(input.value) || 0;
  const variance = physicalStock - systemStock;

  const badge = row.querySelector('.variance-badge');
  badge.innerText = (variance > 0 ? '+' : '') + variance;

  if (variance > 0) {
   badge.className = 'variance-badge px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg font-black text-[10px]';
  } else if (variance < 0) {
   badge.className = 'variance-badge px-3 py-1 bg-rose-100 text-rose-600 rounded-lg font-black text-[10px]';
  } else {
   badge.className = 'variance-badge px-3 py-1 bg-slate-100 text-slate-400 rounded-lg font-black text-[10px]';
  }
 }
</script>
@endsection