@extends('layouts.app')

@section('title', 'Register Staff')
@section('page_title', 'Access Provisioning')

@section('content')
<div class="max-w-4xl mx-auto">
 <div class="mb-8">
  <a href="{{ route('users.index') }}" class="group flex items-center space-x-3 text-slate-400 hover:text-indigo-600 transition-colors">
   <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:shadow-lg group-hover:shadow-indigo-500/10 transition-all">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
    </svg>
   </div>
   <span class="text-xs font-black uppercase tracking-widest">Back to Registry</span>
  </a>
 </div>

 <div class="premium-card !p-0 overflow-hidden bg-white">
  <div class="p-10 bg-slate-950 text-white flex items-center justify-between">
   <div class="space-y-1">
    <h2 class="text-2xl font-black tracking-tight">Access Initialization</h2>
    <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Deploy new personnel to operational stations</p>
   </div>
   <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center">
    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
    </svg>
   </div>
  </div>

  @include('users.form')
 </div>
</div>
@endsection