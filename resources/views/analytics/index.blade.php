@extends('layouts.app')

@section('title', 'Business Intelligence')
@section('page_title', 'Core Business Analytics')

@section('content')
<div class="space-y-10">
 <!-- Top Row: Heatmap -->
 <div class="premium-card p-10 relative overflow-hidden">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
   <div>
    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Hourly Sales Heatmap</h3>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Traffic density over the last 7 days</p>
   </div>
   <div class="flex items-center gap-4">
    <div class="flex items-center gap-2">
     <div class="w-3 h-3 bg-slate-100 rounded"></div>
     <span class="text-[9px] font-black uppercase text-slate-400">Quiet</span>
    </div>
    <div class="flex items-center gap-2">
     <div class="w-3 h-3 bg-indigo-600 rounded"></div>
     <span class="text-[9px] font-black uppercase text-slate-400">Peak</span>
    </div>
   </div>
  </div>

  <div class="grid grid-cols-12 md:grid-cols-24 gap-2 md:gap-3 h-40">
   @foreach($heatmap as $hour => $count)
   @php
   $max = max($heatmap) ?: 1;
   $opacity = ($count / $max);
   $colorClass = $count == 0 ? 'bg-slate-50' : 'bg-indigo-600';
   @endphp
   <div class="group relative h-full flex flex-col justify-end">
    <div class="heatmap-bar {{ $colorClass }} rounded-lg transition-all duration-500 hover:scale-110"
     data-height="{{ max(10, $opacity * 100) }}"
     data-opacity="{{ max(0.1, $opacity) }}">
    </div>
    <div class="absolute inset-x-0 -bottom-8 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap text-center">
     <p class="text-[9px] font-black text-indigo-600 uppercase">{{ $hour }}:00</p>
     <p class="text-[8px] font-bold text-slate-400 uppercase">{{ $count }} Order</p>
    </div>
   </div>
   @endforeach
  </div>
  <div class="mt-8 grid grid-cols-6 text-[8px] font-black text-slate-300 uppercase tracking-widest text-center border-t border-slate-50 pt-4">
   <span>00:00 - 04:00</span>
   <span>04:00 - 08:00</span>
   <span>08:00 - 12:00</span>
   <span>12:00 - 16:00</span>
   <span>16:00 - 20:00</span>
   <span>20:00 - 00:00</span>
  </div>
 </div>

 <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
  <!-- Best Sellers -->
  <div class="premium-card p-10">
   <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-8">Performance: Best Sellers</h4>
   <div class="space-y-6">
    @foreach($topProducts as $idx => $item)
    <div class="flex items-center gap-6 group">
     <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center font-black text-slate-400 group-hover:bg-rose-50 group-hover:text-rose-600 transition-colors">
      #{{ $idx + 1 }}
     </div>
     <div class="flex-1">
      <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $item->product->name }}</p>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ number_format($item->total_qty) }} units sold</p>
     </div>
     <div class="text-right">
      <p class="text-xs font-black text-slate-900">Rp{{ number_format($item->total_revenue, 0, ',', '.') }}</p>
      <div class="w-24 h-1.5 bg-slate-100 rounded-full mt-2 overflow-hidden">
       <div class="revenue-bar h-full bg-rose-500 rounded-full" data-width="{{ ($item->total_revenue / $topProducts->first()->total_revenue) * 100 }}"></div>
      </div>
     </div>
    </div>
    @endforeach
   </div>
  </div>

  <!-- Deadstock -->
  <div class="premium-card p-10">
   <h4 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] mb-8">Alert: Deadstock (30D Inactive)</h4>
   <div class="space-y-6">
    @forelse($deadstock as $p)
    <div class="flex items-center gap-6 group">
     <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 font-black">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
      </svg>
     </div>
     <div class="flex-1">
      <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $p->name }}</p>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Asset tied: {{ $p->stock }} units</p>
     </div>
     <div class="text-right">
      <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Action Required</p>
      <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Stagnant Capital</p>
     </div>
    </div>
    @empty
    <div class="py-10 text-center text-slate-400 font-black uppercase tracking-widest text-[10px]">Ecosystem flow healthy</div>
    @endforelse
   </div>
  </div>
 </div>

 <!-- Staff Performance -->
 <div class="premium-card p-10">
  <h4 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.3em] mb-8">Node Performance: Human Assets</h4>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
   @foreach($staffPerformance as $staff)
   <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 group hover:bg-slate-950 hover:text-white transition-all duration-500">
    <div class="flex items-center gap-4 mb-6">
     <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black">
      {{ substr($staff->user->name, 0, 1) }}
     </div>
     <div>
      <p class="text-sm font-black uppercase tracking-tight">{{ $staff->user->name }}</p>
      <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">{{ $staff->user->role }}</p>
     </div>
    </div>
    <div class="space-y-4">
     <div class="flex justify-between items-end">
      <span class="text-[9px] font-black uppercase tracking-widest opacity-40">Orders Processed</span>
      <span class="text-lg font-black tracking-tighter">{{ $staff->total_orders }}</span>
     </div>
     <div class="flex justify-between items-end">
      <span class="text-[9px] font-black uppercase tracking-widest opacity-40">Total Sales Generated</span>
      <span class="text-lg font-black tracking-tighter">Rp{{ number_format($staff->total_sales, 0, ',', '.') }}</span>
     </div>
    </div>
   </div>
   @endforeach
  </div>
 </div>
</div>

<script>
 document.querySelectorAll('.heatmap-bar').forEach(function(el) {
  el.style.height = el.dataset.height + '%';
  el.style.opacity = el.dataset.opacity;
 });
 document.querySelectorAll('.revenue-bar').forEach(function(el) {
  el.style.width = el.dataset.width + '%';
 });
</script>
@endsection