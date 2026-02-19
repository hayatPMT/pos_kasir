@extends('layouts.app')

@section('title', isset($supplier) ? 'Edit Partner' : 'Partner Onboarding')
@section('page_title', isset($supplier) ? 'Edit Entity' : 'New Ecosystem Partner')

@section('content')
<div class="max-w-2xl mx-auto">
 <div class="mb-8">
  <a href="{{ route('suppliers.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 flex items-center gap-2 transition-colors">
   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
   </svg>
   Back to Directory
  </a>
 </div>

 <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}" method="POST" class="premium-card p-10 space-y-8">
  @csrf
  @if(isset($supplier)) @method('PUT') @endif

  <div class="space-y-6">
   <div class="space-y-2">
    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Supplier Entity Name</label>
    <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" required
     class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 outline-none"
     placeholder="e.g. GLOBAL LOGISTICS INC">
   </div>

   <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Primary Email</label>
     <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"
      class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 outline-none"
      placeholder="contact@entity.com">
    </div>
    <div class="space-y-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Operations Contact</label>
     <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"
      class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 outline-none"
      placeholder="+62 812-XXXX-XXXX">
    </div>
   </div>

   <div class="space-y-2">
    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Key Account Manager</label>
    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}"
     class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 outline-none"
     placeholder="Full legal name of POC">
   </div>

   <div class="space-y-2">
    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Corporate Address</label>
    <textarea name="address" rows="3"
     class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 outline-none"
     placeholder="Full operational headquarters address">{{ old('address', $supplier->address ?? '') }}</textarea>
   </div>

   @if(isset($supplier))
   <div class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl border border-slate-100">
    <div class="flex-1">
     <p class="text-xs font-black text-slate-900 uppercase tracking-tight">Ecosystem Status</p>
     <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Toggle partner participation</p>
    </div>
    <label class="relative inline-flex items-center cursor-pointer">
     <input type="hidden" name="is_active" value="0">
     <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $supplier->is_active ?? 1) ? 'checked' : '' }}>
     <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-indigo-600"></div>
    </label>
   </div>
   @endif
  </div>

  <button type="submit" class="w-full bg-slate-950 text-white py-6 rounded-[2rem] font-black uppercase tracking-[0.3em] text-[11px] shadow-2xl shadow-indigo-500/20 hover:bg-slate-900 hover:scale-[1.01] active:scale-100 transition-all outline-none">
   {{ isset($supplier) ? 'Sync Entity Delta' : 'Authorize Partner Registration' }}
  </button>
 </form>
</div>
@endsection