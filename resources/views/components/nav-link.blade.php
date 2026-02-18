@props(['active', 'href', 'icon'])

@php
$classes = ($active ?? false)
? 'flex items-center space-x-3 px-4 py-3 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 transition-all duration-300 group'
: 'flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all duration-300 group';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
 <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"></path>
 </svg>
 <span class="font-medium text-sm">{{ $slot }}</span>
</a>