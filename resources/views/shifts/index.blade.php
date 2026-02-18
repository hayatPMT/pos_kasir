@extends('layouts.app')

@section('title', 'Shift Management')
@section('page_title', 'Personnel Shifts')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
 <!-- Shift Control Card -->
 <div class="lg:col-span-1 space-y-8">
  @if($activeShift)
  <div class="bg-indigo-950 rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden group">
   <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl transition-transform group-hover:scale-110"></div>
   <div class="relative z-10">
    <div class="inline-flex px-4 py-1.5 bg-emerald-500 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] mb-6">Status: Active Service</div>
    <h3 class="text-3xl font-black tracking-tight mb-2">Ongoing Shift</h3>
    <p class="text-indigo-200 text-xs mb-8">Started at {{ $activeShift->start_time->format('H:i') }} • {{ $activeShift->branch->name }}</p>

    <div class="p-6 bg-white/5 rounded-2xl border border-white/10 mb-8">
     <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Starting Balance</p>
     <p class="text-2xl font-black">Rp{{ number_format($activeShift->starting_cash) }}</p>
    </div>

    <form action="{{ route('shifts.close') }}" method="POST" class="space-y-4">
     @csrf
     <div class="space-y-1.5">
      <label class="text-[9px] font-black text-white/40 uppercase tracking-widest pl-1">Actual Cash in Drawer</label>
      <input type="number" name="actual_cash" required class="w-full bg-white/10 border border-white/10 rounded-xl px-5 py-3.5 text-white font-bold focus:bg-white/20 outline-none" placeholder="Enter amount...">
     </div>
     <div class="space-y-1.5">
      <label class="text-[9px] font-black text-white/40 uppercase tracking-widest pl-1">Closing Notes</label>
      <textarea name="notes" rows="2" class="w-full bg-white/10 border border-white/10 rounded-xl px-5 py-3.5 text-white text-xs outline-none focus:bg-white/20" placeholder="Optional comments..."></textarea>
     </div>
     <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-rose-500/20">End Work Shift</button>
     <a href="{{ route('pos.index') }}" class="w-full inline-flex items-center justify-center bg-white text-indigo-950 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all mt-2">Open Terminal</a>
    </form>
   </div>
  </div>
  @else
  <div class="bg-white rounded-[3rem] p-10 shadow-2xl border border-slate-100 relative group overflow-hidden">
   <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50 rounded-full blur-3xl transition-transform group-hover:scale-110"></div>
   <div class="relative z-10">
    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mb-8 border border-indigo-100">
     <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
     </svg>
    </div>
    <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-2">New Shift</h3>
    <p class="text-slate-400 text-xs mb-10">Start your work session by recording the starting petty cash.</p>

    <form action="{{ route('shifts.open') }}" method="POST" class="space-y-6">
     @csrf
     <div class="space-y-2">
      <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Starting Petty Cash (Rp)</label>
      <input type="number" name="starting_cash" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-xl font-black text-slate-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all" placeholder="0">
     </div>
     <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-indigo-600/20 active:scale-95 transition-all">Start Session</button>
    </form>
   </div>
  </div>
  @endif
 </div>

 <!-- Shift History -->
 <div class="lg:col-span-2">
  <div class="premium-card !p-0 overflow-hidden min-h-[600px] flex flex-col bg-white">
   <div class="p-8 border-b border-slate-50 flex items-center justify-between">
    <div>
     <h3 class="text-xl font-black text-slate-900 tracking-tight">Shift Logs</h3>
     <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Audit Trail</p>
    </div>
   </div>
   <div class="flex-1 overflow-x-auto">
    <table class="w-full text-left">
     <thead>
      <tr class="text-slate-400 text-[9px] font-black uppercase tracking-widest border-b border-slate-50">
       <th class="px-8 py-5">Personnel</th>
       <th class="px-8 py-5">Session</th>
       <th class="px-8 py-5 text-right">Cash Out</th>
       <th class="px-8 py-5 text-center">Variance</th>
      </tr>
     </thead>
     <tbody class="divide-y divide-slate-50">
      @foreach($shifts as $s)
      <tr class="hover:bg-slate-50/50 transition-colors group">
       <td class="px-8 py-6">
        <div class="flex items-center space-x-4">
         <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center font-black text-xs border border-slate-100">{{ substr($s->user->name, 0, 1) }}</div>
         <div class="flex flex-col">
          <span class="font-black text-slate-900 text-sm tracking-tight">{{ $s->user->name }}</span>
          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $s->branch->name }}</span>
         </div>
        </div>
       </td>
       <td class="px-8 py-6">
        <div class="flex flex-col">
         <span class="text-xs font-black text-slate-700">{{ $s->start_time->format('d M') }}</span>
         <span class="text-[10px] font-bold text-slate-400 uppercase italic">{{ $s->start_time->format('H:i') }} → {{ $s->end_time ? $s->end_time->format('H:i') : 'LIVE' }}</span>
        </div>
       </td>
       <td class="px-8 py-6 text-right">
        <span class="font-black text-slate-900">Rp{{ number_format($s->actual_cash) }}</span>
       </td>
       <td class="px-8 py-6 text-center">
        @if($s->status === 'closed')
        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $s->difference == 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
         {{ $s->difference >= 0 ? '+' : '' }}{{ number_format($s->difference) }}
        </span>
        @else
        <span class="animate-pulse px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[9px] font-black uppercase tracking-widest">Ongoing</span>
        @endif
       </td>
      </tr>
      @endforeach
     </tbody>
    </table>
   </div>
   <div class="p-8 border-t border-slate-50">
    {{ $shifts->links() }}
   </div>
  </div>
 </div>
</div>
@endsection