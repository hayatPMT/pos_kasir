@extends('layouts.app')

@section('title', isset($branch) ? 'Refine Node: ' . $branch->name : 'Establish New Node')
@section('page_title', 'Operational Node Configuration')

@section('content')
<div class="max-w-4xl mx-auto">
 <div class="mb-8">
  <a href="{{ route('branches.index') }}" class="group flex items-center space-x-3 text-slate-400 hover:text-indigo-600 transition-colors">
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
    <h2 class="text-2xl font-black tracking-tight">{{ isset($branch) ? 'Edit Node Parameters' : 'Register Operational Station' }}</h2>
    <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Configure geographic and operational settings</p>
   </div>
   <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center">
    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
    </svg>
   </div>
  </div>

  <form action="{{ isset($branch) ? route('branches.update', $branch->id) : route('branches.store') }}" method="POST" class="p-12 space-y-12">
   @csrf
   @if(isset($branch)) @method('PUT') @endif

   <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
    <!-- Name -->
    <div class="flex flex-col">
     <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Station Identity (Name)</label>
     <div class="relative group">
      <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
       </svg>
      </span>
      <input type="text" id="name" name="name" value="{{ old('name', $branch->name ?? '') }}" required
       class="input-premium pl-14" placeholder="e.g. Headquarters East">
     </div>
     @error('name') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
    </div>

    <!-- Code -->
    <div class="flex flex-col">
     <label for="code" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Unique Node ID (Code)</label>
     <div class="relative group">
      <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
       </svg>
      </span>
      <input type="text" id="code" name="code" value="{{ old('code', $branch->code ?? '') }}" required
       class="input-premium pl-14 uppercase" placeholder="e.g. HQ-001">
     </div>
     @error('code') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
    </div>

    <!-- Phone -->
    <div class="flex flex-col">
     <label for="phone" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Comms Link (Phone)</label>
     <div class="relative group">
      <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
       </svg>
      </span>
      <input type="text" id="phone" name="phone" value="{{ old('phone', $branch->phone ?? '') }}"
       class="input-premium pl-14" placeholder="+62 ...">
     </div>
     @error('phone') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
    </div>

    <!-- Email -->
    <div class="flex flex-col">
     <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Digital Node (Email)</label>
     <div class="relative group">
      <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
       </svg>
      </span>
      <input type="email" id="email" name="email" value="{{ old('email', $branch->email ?? '') }}"
       class="input-premium pl-14" placeholder="branch@enterprise.com">
     </div>
     @error('email') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
    </div>

    <!-- Address -->
    <div class="flex flex-col md:col-span-2">
     <label for="address" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Geographic Coordinates (Address)</label>
     <div class="relative group">
      <span class="absolute top-4 left-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
       </svg>
      </span>
      <textarea id="address" name="address" rows="3"
       class="input-premium pl-14 py-4 !h-auto resize-none" placeholder="Enter full geographic address...">{{ old('address', $branch->address ?? '') }}</textarea>
     </div>
    </div>

    <!-- Status -->
    <div class="flex flex-col">
     <label for="is_active" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Node Operational Status</label>
     <div class="relative group">
      <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors shadow-inner">
       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
       </svg>
      </span>
      <select id="is_active" name="is_active" required class="input-premium pl-14 appearance-none">
       <option value="1" {{ old('is_active', $branch->is_active ?? '1') == '1' ? 'selected' : '' }}>Operational (Active)</option>
       <option value="0" {{ old('is_active', $branch->is_active ?? '1') == '0' ? 'selected' : '' }}>Suspended (Inactive)</option>
      </select>
     </div>
    </div>
   </div>

   <div class="pt-12 flex items-center space-x-6 border-t border-slate-50">
    <button type="submit" class="btn-primary !px-12 !py-4 group">
     <span class="text-xs uppercase tracking-[0.2em] font-black">
      {{ isset($branch) ? 'Authorize Parameters' : 'Deploy Station' }}
     </span>
     <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
     </svg>
    </button>
    <a href="{{ route('branches.index') }}" class="text-[10px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest transition-colors">Abort Deployment</a>
   </div>
  </form>
 </div>
</div>
@endsection