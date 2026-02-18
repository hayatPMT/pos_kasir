@extends('layouts.app')

@section('title', 'Modify Access: ' . $user->name)
@section('page_title', 'Access Privilege Update')

@section('content')
<div class="max-w-4xl mx-auto">
 <div class="mb-8">
  <a href="{{ route('users.index') }}" class="group flex items-center space-x-3 text-slate-400 hover:text-indigo-600 transition-colors">
   <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-indigo-500/10 transition-all">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
    </svg>
   </div>
   <span class="text-xs font-black uppercase tracking-widest">Return to Personnel Ledger</span>
  </a>
 </div>

 <div class="premium-card !p-0 overflow-hidden bg-white">
  <div class="p-10 bg-slate-950 text-white flex items-center justify-between">
   <div class="flex items-center space-x-6">
    <div class="w-20 h-20 rounded-2xl bg-white/10 p-1 border border-white/20">
     <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff" class="w-full h-full object-cover rounded-xl shadow-2xl">
    </div>
    <div class="space-y-1">
     <h2 class="text-2xl font-black tracking-tight">{{ $user->name }}</h2>
     <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">{{ $user->role }} • Assigned to {{ $user->branch->name ?? 'Central Command' }}</p>
    </div>
   </div>
   <div class="hidden md:flex flex-col items-end opacity-40">
    <p class="text-[10px] font-black uppercase tracking-[0.2em]">Last Modified</p>
    <p class="text-xs font-bold">{{ $user->updated_at->format('d M Y, H:i') }}</p>
   </div>
  </div>

  @include('users.form')
 </div>
</div>
@endsection