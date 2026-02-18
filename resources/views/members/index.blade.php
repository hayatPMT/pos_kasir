@extends('layouts.app')

@section('title', 'Members')
@section('page_title', 'Member Management')

@section('content')
<div class="premium-card overflow-hidden !shadow-none border-none">
    <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex flex-col">
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Active Customers</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Registry of all registered members</p>
        </div>

        <div class="flex items-center gap-4">
            <form action="{{ route('members.index') }}" method="GET" class="relative group">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full md:w-80 pl-12 pr-6 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold placeholder-slate-300 shadow-inner"
                    placeholder="Search identity or code...">
            </form>

            <a href="{{ route('members.create') }}"
                class="btn-primary group">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-xs uppercase tracking-widest">Register New</span>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-b border-slate-50 bg-slate-50/30">
                    <th class="px-10 py-5">Profile Discovery</th>
                    <th class="px-10 py-5">Communication</th>
                    <th class="px-10 py-5">Loyalty Points</th>
                    <th class="px-10 py-5">Status</th>
                    <th class="px-10 py-5 text-right">Operational</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($members as $member)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-10 py-6">
                        <div class="flex items-center space-x-5">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black text-lg border border-indigo-100/50 shadow-sm transition-transform group-hover:rotate-6">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate tracking-tight">{{ $member->name }}</h4>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mt-0.5">{{ $member->member_code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-slate-700 block tracking-tight">{{ $member->phone }}</p>
                            <p class="text-[10px] font-bold text-slate-400 group-hover:text-slate-500 transition-colors">{{ $member->email ?? 'No email associated' }}</p>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <span class="inline-flex items-center space-x-2 px-4 py-1.5 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-amber-100 shadow-sm shadow-amber-500/5 group-hover:bg-amber-100 transition-colors">
                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span>{{ number_format($member->points) }} PTS</span>
                        </span>
                    </td>
                    <td class="px-10 py-6">
                        <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm {{ $member->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100 animate-pulse' }}">
                            {{ $member->is_active ? 'Authenticated' : 'Suspended' }}
                        </span>
                    </td>
                    <td class="px-10 py-6 text-right">
                        <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
                            <a href="{{ route('members.edit', $member->id) }}" class="p-3 text-slate-400 hover:text-indigo-600 bg-white hover:shadow-xl hover:shadow-indigo-500/10 rounded-2xl border border-slate-100 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Archive this member?')" class="inline">
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
                    <td colspan="5" class="px-10 py-32 text-center overflow-hidden relative">
                        <div class="absolute inset-0 bg-slate-50/20 backdrop-blur-sm -z-10"></div>
                        <div class="flex flex-col items-center">
                            <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex items-center justify-center mb-10 group hover:scale-110 transition-transform duration-700">
                                <svg class="w-14 h-14 text-slate-100 group-hover:text-indigo-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">No member profiles found</h3>
                            <p class="text-slate-400 max-w-sm mx-auto mb-10 font-medium">Capture your customer data to build long-term relationships and drive sales through loyalty.</p>
                            <a href="{{ route('members.create') }}" class="btn-primary !px-12 !py-5 !rounded-[2rem]">Initialize Database</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($members->hasPages())
    <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection