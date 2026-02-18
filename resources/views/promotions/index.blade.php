@extends('layouts.app')

@section('title', 'Promotions')
@section('page_title', 'Campaign Management')

@section('content')
<div class="premium-card overflow-hidden !shadow-none border-none">
    <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex flex-col">
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Active Campaigns</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Driving revenue through strategic marketing</p>
        </div>

        <div class="flex items-center gap-4">
            <form action="{{ route('promotions.index') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full md:w-80 pl-12 pr-6 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold placeholder-slate-300 shadow-inner"
                    placeholder="Search campaigns...">
            </form>

            <a href="{{ route('promotions.create') }}"
                class="btn-primary group">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-xs uppercase tracking-widest">Construct New</span>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-b border-slate-50 bg-slate-50/30">
                    <th class="px-10 py-5">Campaign Identity</th>
                    <th class="px-10 py-5">Mechanism</th>
                    <th class="px-10 py-5">Reward Value</th>
                    <th class="px-10 py-5">Validity Period</th>
                    <th class="px-10 py-5">Lifespan</th>
                    <th class="px-10 py-5 text-right">Operational</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($promotions as $promo)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-10 py-6">
                        <div class="flex items-center space-x-5">
                            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center border border-rose-100/50 shadow-sm transition-transform group-hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate tracking-tight">{{ $promo->name }}</h4>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Min Purchase: Rp{{ number_format($promo->min_purchase) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <span class="px-4 py-1.5 bg-slate-100 text-slate-600 rounded-xl text-[9px] font-black uppercase tracking-widest border border-slate-200">
                            {{ str_replace('_', ' ', $promo->type) }}
                        </span>
                    </td>
                    <td class="px-10 py-6">
                        <span class="text-lg font-black text-indigo-600 tracking-tighter">
                            @if($promo->type == 'percentage')
                            {{ $promo->value }}%
                            @elseif($promo->type == 'nominal')
                            Rp{{ number_format($promo->value) }}
                            @else
                            {{ $promo->value }} Units
                            @endif
                        </span>
                    </td>
                    <td class="px-10 py-6">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                <p class="text-[10px] font-black text-slate-700 uppercase">{{ \Illuminate\Support\Carbon::parse($promo->start_date)->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase">{{ \Illuminate\Support\Carbon::parse($promo->end_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        @php
                        $now = now();
                        $endDate = \Illuminate\Support\Carbon::parse($promo->end_date);
                        $startDate = \Illuminate\Support\Carbon::parse($promo->start_date);
                        $isExpired = $now->gt($endDate);
                        $isUpcoming = $now->lt($startDate);
                        @endphp
                        <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm 
                            @if($isExpired) bg-slate-100 text-slate-400 border-slate-200
                            @elseif($isUpcoming) bg-amber-50 text-amber-600 border-amber-100 animate-pulse
                            @elseif($promo->is_active) bg-emerald-50 text-emerald-600 border-emerald-100
                            @else bg-rose-50 text-rose-600 border-rose-100
                            @endif">
                            @if($isExpired) Decommissioned
                            @elseif($isUpcoming) Scheduled
                            @elseif($promo->is_active) Operational
                            @else Suspended
                            @endif
                        </span>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                            <a href="{{ route('promotions.edit', $promo->id) }}" class="p-3 text-slate-400 hover:text-indigo-600 bg-white hover:shadow-xl hover:shadow-indigo-500/10 rounded-2xl border border-slate-100 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Archive this campaign?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 text-slate-400 hover:text-rose-600 bg-white hover:shadow-xl hover:shadow-rose-500/10 rounded-2xl border border-slate-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-10 py-32 text-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-slate-50/20 backdrop-blur-sm -z-10"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex items-center justify-center mb-10 group hover:scale-110 transition-transform duration-700">
                                <svg class="w-14 h-14 text-slate-100 group-hover:text-rose-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">No marketing activity</h3>
                            <p class="text-slate-400 max-w-sm mx-auto mb-10 font-medium">Boost your revenue by creating strategic discounts and rewards. Your first campaign is just a click away.</p>
                            <a href="{{ route('promotions.create') }}" class="btn-primary !px-12 !py-5 !rounded-[2rem]">Launch Campaign</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($promotions->hasPages())
    <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
        {{ $promotions->links() }}
    </div>
    @endif
</div>
@endsection