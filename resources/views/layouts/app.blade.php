<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>@yield('title', 'Dashboard') - POS Kasir</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-background antialiased selection:bg-indigo-100 selection:text-indigo-600">
 <div class="min-h-screen flex">
  <!-- Sidebar -->
  <aside class="w-72 bg-slate-950 text-white flex-shrink-0 fixed h-full z-50 transition-all duration-500 ease-in-out border-r border-white/5">
   <div class="h-full flex flex-col p-8">
    <div class="flex items-center space-x-4 mb-12 group cursor-pointer">
     <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-600/40 group-hover:rotate-12 transition-transform duration-500">
      <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
      </svg>
     </div>
     <div class="flex flex-col">
      <span class="text-xl font-extrabold tracking-tight leading-none">POS<span class="text-indigo-500">KASIR</span></span>
      <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em] mt-1">Enterprise Edition</span>
     </div>
    </div>

    <nav class="flex-1 space-y-1.5 scrollbar-hide overflow-y-auto pr-2">
     <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6">Dashboard</x-nav-link>
     <x-nav-link href="{{ route('pos.index') }}" :active="request()->routeIs('pos.*')" icon="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">Cashier POS</x-nav-link>

     <div class="pt-8 pb-3 px-4">
      <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">Operational</p>
     </div>
     <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')" icon="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">Inventory</x-nav-link>
     <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.*')" icon="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">Customer Base</x-nav-link>
     <x-nav-link href="{{ route('promotions.index') }}" :active="request()->routeIs('promotions.*')" icon="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">Marketing</x-nav-link>

     <div class="pt-8 pb-3 px-4">
      <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.3em]">Administration</p>
     </div>
     <x-nav-link href="{{ route('branches.index') }}" :active="request()->routeIs('branches.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">Branches</x-nav-link>
     <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">Teams</x-nav-link>
     <x-nav-link href="{{ route('settings.index') }}" :active="request()->routeIs('settings.*')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z">Configuration</x-nav-link>
    </nav>

    <div class="mt-auto pt-8 border-t border-white/5">
     <div class="p-5 bg-white/5 rounded-2xl border border-white/5 flex items-center space-x-4">
      <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-sm">
       {{ substr(Auth::user()->name, 0, 1) }}
      </div>
      <div class="flex-1 min-w-0">
       <p class="text-xs font-bold truncate">{{ Auth::user()->name }}</p>
       <p class="text-[10px] text-white/40 font-semibold uppercase tracking-wider">{{ Auth::user()->role }}</p>
      </div>
      <form action="{{ route('logout') }}" method="POST">
       @csrf
       <button type="submit" class="p-1 hover:text-rose-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
       </button>
      </form>
     </div>
    </div>
   </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 ml-72 min-h-screen flex flex-col">
   <!-- Navbar -->
   <header class="h-20 px-10 flex items-center justify-between sticky top-0 z-40 glass-card mx-8 mt-6 rounded-[2rem] border border-white !shadow-xl !shadow-slate-200/40">
    <div class="flex flex-col">
     <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('page_title')</h2>
     <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Management Console</p>
    </div>

    <div class="flex items-center space-x-4">
     <div id="clock" class="text-xs font-black text-slate-400 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 hidden md:block">
      {{ now()->format('H:i') }}
     </div>

     <div class="h-10 w-px bg-slate-100 mx-2"></div>

     <button class="relative p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-300">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
      </svg>
      <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white shadow-sm shadow-rose-500/40"></span>
     </button>

     <a href="{{ route('settings.index') }}" class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-300">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
     </a>
    </div>
   </header>

   <!-- Page Content -->
   <div class="px-8 flex-1 py-10">
    @if (session('success'))
    <div class="mb-8 p-5 glass-card !bg-emerald-50/50 !border-emerald-200/50 text-emerald-700 rounded-2xl flex items-center space-x-4 shadow-xl shadow-emerald-500/5 animate-in fade-in slide-in-from-top-4 duration-500">
     <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>
     </div>
     <div>
      <p class="text-sm font-black uppercase tracking-wider leading-none mb-1">Success</p>
      <p class="text-sm font-semibold opacity-80">{{ session('success') }}</p>
     </div>
    </div>
    @endif

    @yield('content')
   </div>

   <!-- Footer -->
   <footer class="px-10 py-8 text-center bg-white/30 backdrop-blur-sm border-t border-slate-100 mt-auto mx-8 mb-6 rounded-[2rem]">
    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">&copy; {{ date('Y') }} POS KASIR • DESIGNED FOR EXCELLENCE</p>
   </footer>
  </main>
 </div>

 <script>
  // Smooth clock update
  function updateClock() {
   const now = new Date();
   const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
   const clockEl = document.getElementById('clock');
   if (clockEl) clockEl.innerText = timeStr;
  }
  setInterval(updateClock, 10000);
 </script>
</body>

</html>