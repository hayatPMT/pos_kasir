@extends('layouts.app')

@section('title', 'System Configuration')
@section('page_title', 'Application Control Center')

@section('content')
<div class="max-w-5xl mx-auto">
 <form action="{{ route('settings.update') }}" method="POST" class="space-y-10">
  @csrf

  <!-- Store Identity Section -->
  <div class="premium-card !p-0 overflow-hidden bg-white">
   <div class="p-10 bg-slate-950 text-white flex items-center justify-between">
    <div class="space-y-1">
     <h2 class="text-2xl font-black tracking-tight">Organization Profile</h2>
     <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Core operational parameters and branding</p>
    </div>
    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center">
     <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
     </svg>
    </div>
   </div>

   <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
    <div class="flex flex-col">
     <label for="store_name" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Enterprise Title</label>
     <input type="text" id="store_name" name="store_name" value="{{ $settings['store_name'] ?? 'POS Kasir Modern' }}"
      class="input-premium" placeholder="e.g. Acme Corp Terminal">
    </div>

    <div class="flex flex-col">
     <label for="store_email" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Communications Node (Email)</label>
     <input type="email" id="store_email" name="store_email" value="{{ $settings['store_email'] ?? 'contact@enterprise.com' }}"
      class="input-premium" placeholder="contact@company.com">
    </div>

    <div class="flex flex-col">
     <label for="store_phone" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Support Hotline</label>
     <input type="text" id="store_phone" name="store_phone" value="{{ $settings['store_phone'] ?? '+62 123 4567 890' }}"
      class="input-premium" placeholder="+62 ...">
    </div>

    <div class="flex flex-col">
     <label for="store_address" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Geographic Headquarters</label>
     <textarea id="store_address" name="store_address" rows="3"
      class="input-premium !h-auto py-4">{{ $settings['store_address'] ?? 'Central Business District, Floor 42' }}</textarea>
    </div>
   </div>
  </div>

  <!-- Financial Parameters -->
  <div class="premium-card !p-0 overflow-hidden bg-white">
   <div class="p-10 bg-slate-950 text-white flex items-center justify-between">
    <div class="space-y-1">
     <h2 class="text-2xl font-black tracking-tight">Fiscal Logic</h2>
     <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Transaction rules and tax provisions</p>
    </div>
    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center">
     <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
     </svg>
    </div>
   </div>

   <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
    <div class="flex flex-col">
     <label for="currency_symbol" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">International Currency Symbol</label>
     <input type="text" id="currency_symbol" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'Rp' }}"
      class="input-premium" placeholder="e.g. $ or Rp">
    </div>

    <div class="flex flex-col">
     <label for="tax_percentage" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Default Tax Provision (%)</label>
     <input type="number" step="0.01" id="tax_percentage" name="tax_percentage" value="{{ $settings['tax_percentage'] ?? '11.00' }}"
      class="input-premium" placeholder="11.00">
    </div>

    <div class="flex flex-col md:col-span-2">
     <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Financial Safeguards</label>
     <div class="flex flex-col gap-4">
      <label class="flex items-center space-x-4 p-5 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-indigo-200 transition-all cursor-pointer group">
       <input type="checkbox" name="require_member_auth" value="1" {{ ($settings['require_member_auth'] ?? '0') ? 'checked' : '' }}
        class="w-5 h-5 rounded-lg text-indigo-600 border-slate-300 focus:ring-indigo-500">
       <div class="flex-1">
        <p class="text-xs font-black text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-widest">Enforce Member Authentication</p>
        <p class="text-[10px] font-bold text-slate-400 mt-0.5">Prompt for member ID on every terminal session</p>
       </div>
      </label>

      <label class="flex items-center space-x-4 p-5 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-indigo-200 transition-all cursor-pointer group">
       <input type="checkbox" name="auto_print_receipt" value="1" {{ ($settings['auto_print_receipt'] ?? '0') ? 'checked' : '' }}
        class="w-5 h-5 rounded-lg text-indigo-600 border-slate-300 focus:ring-indigo-500">
       <div class="flex-1">
        <p class="text-xs font-black text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-widest">Autonomous Receipt Dispatch</p>
        <p class="text-[10px] font-bold text-slate-400 mt-0.5">Automatically trigger hardware print on settlement</p>
       </div>
      </label>
     </div>
    </div>
   </div>
  </div>

  <!-- Submission Control -->
  <div class="pt-6 flex items-center justify-between">
   <div class="flex items-center space-x-3 text-slate-400">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <p class="text-[10px] font-black uppercase tracking-widest">All changes are logged in the security audit trail</p>
   </div>

   <button type="submit" class="btn-primary !px-16 !py-5 group">
    <span class="text-xs uppercase tracking-[0.2em] font-black">Commit Configuration</span>
    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
   </button>
  </div>
 </form>
</div>
@endsection