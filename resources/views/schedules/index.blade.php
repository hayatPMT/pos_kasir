@extends('layouts.app')

@section('title', 'Staff Scheduling')
@section('page_title', 'Master Shift Schedules')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
 <!-- Add Schedule (Side) -->
 <div class="lg:col-span-1">
  <div class="bg-white rounded-[2.5rem] p-8 shadow-premium border border-slate-100">
   <h3 class="text-xl font-black text-slate-900 tracking-tight mb-6">Plan Shift</h3>

   <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="space-y-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Assign User</label>
     <select name="user_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
      @foreach($users as $user)
      <option value="{{ $user->id }}">{{ $user->name }}</option>
      @endforeach
     </select>
    </div>

    <div class="space-y-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Target Branch</label>
     <select name="branch_id" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
      @foreach($branches as $branch)
      <option value="{{ $branch->id }}">{{ $branch->name }}</option>
      @endforeach
     </select>
    </div>

    <div class="space-y-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Recurrence Day</label>
     <select name="day_of_week" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
      @foreach($days as $day)
      <option value="{{ $day }}">{{ ucfirst($day) }}</option>
      @endforeach
     </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
     <div class="space-y-2">
      <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Start Time</label>
      <input type="time" name="start_time" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none">
     </div>
     <div class="space-y-2">
      <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">End Time</label>
      <input type="time" name="end_time" required class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold outline-none">
     </div>
    </div>

    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-indigo-600/20">Add to Schedule</button>
   </form>
  </div>
 </div>

 <!-- Calendar View (Main) -->
 <div class="lg:col-span-3">
  <div class="bg-white rounded-[2.5rem] shadow-premium border border-slate-100 overflow-hidden">
   <div class="p-8 border-b border-slate-50">
    <h3 class="text-xl font-black text-slate-900 tracking-tight">Weekly Planner</h3>
   </div>
   <div class="overflow-x-auto">
    <table class="w-full text-left">
     <thead>
      <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-50">
       <th class="px-8 py-5">Staff Member</th>
       <th class="px-8 py-5">Day</th>
       <th class="px-8 py-5">Timings</th>
       <th class="px-8 py-5">Branch</th>
       <th class="px-8 py-5 text-right">Actions</th>
      </tr>
     </thead>
     <tbody class="divide-y divide-slate-50">
      @foreach($schedules as $s)
      <tr class="hover:bg-slate-50/50 transition-colors group">
       <td class="px-8 py-6">
        <div class="flex items-center space-x-4">
         <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-black text-xs">{{ substr($s->user->name, 0, 1) }}</div>
         <span class="font-black text-slate-900">{{ $s->user->name }}</span>
        </div>
       </td>
       <td class="px-8 py-6">
        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[9px] font-black uppercase tracking-widest">{{ $s->day_of_week }}</span>
       </td>
       <td class="px-8 py-6 text-sm font-bold text-slate-600">
        {{ date('H:i', strtotime($s->start_time)) }} — {{ date('H:i', strtotime($s->end_time)) }}
       </td>
       <td class="px-8 py-6 text-[10px] font-black uppercase text-slate-400 tracking-widest">
        {{ $s->branch->name }}
       </td>
       <td class="px-8 py-6 text-right">
        <form action="{{ route('schedules.destroy', $s) }}" method="POST" class="inline">
         @csrf @method('DELETE')
         <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
         </button>
        </form>
       </td>
      </tr>
      @endforeach
     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>
@endsection