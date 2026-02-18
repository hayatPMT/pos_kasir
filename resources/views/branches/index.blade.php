@extends('layouts.app')

@section('title', 'Branches')
@section('page_title', 'Branch Management')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
 <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
  <form action="{{ route('branches.index') }}" method="GET" class="flex-1 max-w-md relative">
   <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
   </span>
   <input type="text" name="search" value="{{ request('search') }}"
    class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
    placeholder="Search by name, code, or phone...">
  </form>

  <a href="{{ route('branches.create') }}"
   class="inline-flex items-center space-x-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold transition-all active:scale-95 shadow-lg shadow-slate-900/10">
   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
   </svg>
   <span>Add New Branch</span>
  </a>
 </div>

 <div class="overflow-x-auto">
  <table class="w-full text-left">
   <thead>
    <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-50">
     <th class="px-8 py-4">Branch Info</th>
     <th class="px-8 py-4">Contact</th>
     <th class="px-8 py-4">Status</th>
     <th class="px-8 py-4 text-right">Actions</th>
    </tr>
   </thead>
   <tbody class="divide-y divide-slate-50">
    @forelse ($branches as $branch)
    <tr class="hover:bg-slate-50/50 transition-colors group">
     <td class="px-8 py-5">
      <div class="flex items-center space-x-4">
       <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">
        {{ substr($branch->name, 0, 1) }}
       </div>
       <div class="min-w-0">
        <h4 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $branch->name }}</h4>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $branch->code }}</p>
       </div>
      </div>
     </td>
     <td class="px-8 py-5">
      <div class="space-y-1">
       <p class="text-sm font-semibold text-slate-700 block">{{ $branch->phone ?? '-' }}</p>
       <p class="text-xs text-slate-400">{{ $branch->email ?? '-' }}</p>
      </div>
     </td>
     <td class="px-8 py-5">
      <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $branch->is_active ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
       {{ $branch->is_active ? 'Active' : 'Inactive' }}
      </span>
     </td>
     <td class="px-8 py-5 text-right">
      <div class="flex items-center justify-end space-x-2">
       <a href="{{ route('branches.edit', $branch->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 rounded-xl transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
       </a>
       <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 bg-slate-50 hover:bg-rose-50 rounded-xl transition-all">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
         </svg>
        </button>
       </form>
      </div>
     </td>
    </tr>
    @empty
    <tr>
     <td colspan="4" class="px-8 py-20 text-center">
      <div class="flex flex-col items-center">
       <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
        <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
       </div>
       <h3 class="text-xl font-bold text-slate-900 mb-2">No branches found</h3>
       <p class="text-slate-400 max-w-xs mx-auto mb-8">Start by adding your business locations here.</p>
       <a href="{{ route('branches.create') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20">Add Branch</a>
      </div>
     </td>
    </tr>
    @endforelse
   </tbody>
  </table>
 </div>

 @if ($branches->hasPages())
 <div class="p-8 border-t border-slate-50 bg-slate-50/30">
  {{ $branches->links() }}
 </div>
 @endif
</div>
@endsection