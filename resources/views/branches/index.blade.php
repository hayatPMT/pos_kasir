@extends('layouts.app')

@section('title', 'Branches')
@section('page_title', 'Global Branch Registry')

@section('content')
<div class="premium-card overflow-hidden !shadow-none border-none">
  <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="flex flex-col">
      <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Active Operations</h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Operational nodes and distribution centers</p>
    </div>

    <div class="flex items-center gap-4">
      <form action="{{ route('branches.index') }}" method="GET" class="relative group">
        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}"
          class="w-full md:w-80 pl-12 pr-6 py-3.5 rounded-2xl bg-slate-50 border border-slate-100 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all text-sm font-bold placeholder-slate-300 shadow-inner"
          placeholder="Search node name or code...">
      </form>

      <a href="{{ route('branches.create') }}"
        class="btn-primary group">
        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        <span class="text-xs uppercase tracking-widest">Establish Node</span>
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-b border-slate-50 bg-slate-50/30">
          <th class="px-10 py-5">Operational Entity</th>
          <th class="px-10 py-5">Communication node</th>
          <th class="px-10 py-5">Geographic deployment</th>
          <th class="px-10 py-5">Lifespan</th>
          <th class="px-10 py-5 text-right">Operational</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-50">
        @forelse ($branches as $branch)
        <tr class="hover:bg-slate-50/50 transition-colors group">
          <td class="px-10 py-6">
            <div class="flex items-center space-x-5">
              <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center border border-indigo-100/50 shadow-sm transition-transform group-hover:scale-110">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <div class="min-w-0">
                <h4 class="font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate tracking-tight">{{ $branch->name }}</h4>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $branch->code }}</p>
              </div>
            </div>
          </td>
          <td class="px-10 py-6">
            <div class="flex flex-col space-y-1">
              <span class="text-xs font-black text-slate-700 tracking-tight flex items-center gap-2">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                {{ $branch->phone ?? 'Unlisted' }}
              </span>
              <span class="text-[10px] font-bold text-slate-400 lowercase tracking-tight flex items-center gap-2">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                {{ $branch->email ?? 'no-email@enterprise.com' }}
              </span>
            </div>
          </td>
          <td class="px-10 py-6">
            <div class="flex items-center space-x-3 text-slate-500">
              <div class="p-2 bg-slate-50 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
              <span class="text-xs font-bold leading-relaxed max-w-[150px] truncate">{{ $branch->address ?? 'Registry Pending' }}</span>
            </div>
          </td>
          <td class="px-10 py-6">
            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border shadow-sm 
                            {{ $branch->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-400 border-slate-200' }}">
              {{ $branch->is_active ? 'Operational' : 'Decommissioned' }}
            </span>
          </td>
          <td class="px-10 py-6 text-right">
            <div class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-all transform translate-x-4 group-hover:translate-x-0">
              <a href="{{ route('branches.edit', $branch->id) }}" class="p-3 text-slate-400 hover:text-indigo-600 bg-white hover:shadow-xl hover:shadow-indigo-500/10 rounded-2xl border border-slate-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </a>
              <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Decommission this operational node?')" class="inline">
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
          <td colspan="5" class="px-10 py-32 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-slate-50/20 backdrop-blur-sm -z-10"></div>
            <div class="flex flex-col items-center">
              <div class="w-32 h-32 bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 flex items-center justify-center mb-10 group hover:scale-110 transition-transform duration-700">
                <svg class="w-14 h-14 text-slate-100 group-hover:text-indigo-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tight">Node registry empty</h3>
              <p class="text-slate-400 max-w-sm mx-auto mb-10 font-medium">Establish your first operational node to start tracking regional sales and inventory.</p>
              <a href="{{ route('branches.create') }}" class="btn-primary !px-12 !py-5 !rounded-[2rem]">Establish Node</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($branches->hasPages())
  <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
    {{ $branches->links() }}
  </div>
  @endif
</div>
@endsection