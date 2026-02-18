<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Customer View - POS KASIR</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-950 text-white font-['Instrument_Sans'] overflow-hidden">
 <div class="min-h-screen flex flex-col relative p-12">
  <!-- Abstract Background -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
   <div class="absolute -top-1/4 -right-1/4 w-[800px] h-[800px] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
   <div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-rose-600/10 rounded-full blur-[100px]"></div>
  </div>

  <!-- Header -->
  <header class="relative z-10 flex items-center justify-between mb-16">
   <div class="flex items-center space-x-6">
    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-600/40">
     <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
     </svg>
    </div>
    <div>
     <h1 class="text-2xl font-black tracking-tight tracking-[0.1em] uppercase">POS KASIR</h1>
     <p class="text-[10px] font-bold text-white/30 tracking-[0.4em] uppercase">Premium Checkout Experience</p>
    </div>
   </div>
   <div class="text-right">
    <p class="text-4xl font-black tracking-tighter" id="clock">00:00:00</p>
    <p id="date-label" class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em] mt-2"></p>
   </div>
  </header>

  <!-- Main Display Area -->
  <main class="flex-1 flex gap-12 relative z-10">
   <!-- Order Summary (Left) -->
   <div class="flex-[1.5] flex flex-col bg-white/5 backdrop-blur-xl rounded-[3.5rem] border border-white/10 overflow-hidden shadow-2xl">
    <div class="p-10 border-b border-white/5 bg-white/[0.02]">
     <h2 class="text-xl font-black tracking-widest uppercase">Your Selection</h2>
    </div>
    <div id="display-items" class="flex-1 p-10 space-y-8 overflow-y-auto">
     <!-- Items go here -->
     <div class="h-full flex flex-col items-center justify-center space-y-12 opacity-30 py-20 translate-y-[-20%]">
      <div class="w-32 h-32 rounded-full border-4 border-dashed border-white/20 flex items-center justify-center animate-[spin_20s_linear_infinite]">
       <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
       </svg>
      </div>
      <p class="text-lg font-black uppercase tracking-[0.3em]">Ready to serve you</p>
     </div>
    </div>
   </div>

   <!-- Totals (Right) -->
   <div class="flex-1 flex flex-col justify-end">
    <div class="space-y-10">
     <div class="p-10 bg-indigo-600 rounded-[3rem] shadow-2xl shadow-indigo-600/40 relative overflow-hidden group">
      <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
      <p class="text-[12px] font-black text-white/50 uppercase tracking-[0.3em] mb-4">Amount to Pay</p>
      <h3 class="text-7xl font-black tracking-tighter" id="display-total">Rp 0</h3>
      <div class="mt-8 pt-8 border-t border-white/10 flex justify-between items-center">
       <span class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">Subtotal</span>
       <span class="text-xl font-bold" id="display-subtotal">Rp 0</span>
      </div>
     </div>

     <div class="bg-white/5 backdrop-blur-md p-10 rounded-[3rem] border border-white/5">
      <div class="flex items-center space-x-6 text-emerald-400">
       <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center border border-emerald-500/20">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
       </div>
       <div>
        <p class="text-lg font-black tracking-tight">Active Terminal</p>
        <p class="text-[10px] uppercase font-bold text-white/20 tracking-widest">Branch: Headquarters</p>
       </div>
      </div>
     </div>
    </div>
   </div>
  </main>

  <!-- Footer -->
  <footer class="relative z-10 mt-16 text-center">
   <p class="text-[9px] font-black text-white/10 uppercase tracking-[0.8em]">Designed for Excellence & Transparency</p>
  </footer>
 </div>

 <script>
  const fmt = new Intl.NumberFormat('id-ID');

  function updateClock() {
   const now = new Date();
   document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', {
    hour12: false
   });
   document.getElementById('date-label').innerText = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
   });
  }
  setInterval(updateClock, 1000);
  updateClock();

  async function pollCart() {
   try {
    const resp = await fetch('{{ route("pos.display.data") }}');
    const data = await resp.json();
    renderDisplay(data);
   } catch (err) {
    console.error('Display sync error', err);
   }
  }

  function renderDisplay(data) {
   const itemsBox = document.getElementById('display-items');
   const totalEl = document.getElementById('display-total');
   const subtotalEl = document.getElementById('display-subtotal');

   totalEl.innerText = 'Rp ' + fmt.format(data.total);
   subtotalEl.innerText = 'Rp ' + fmt.format(data.subtotal);

   if (data.items.length === 0) {
    if (!itemsBox.querySelector('.opacity-30')) {
     itemsBox.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center space-y-12 opacity-30 py-20 translate-y-[-20%] animate-in fade-in duration-1000">
                        <div class="w-32 h-32 rounded-full border-4 border-dashed border-white/20 flex items-center justify-center animate-[spin_20s_linear_infinite]">
                             <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-lg font-black uppercase tracking-[0.3em]">Ready to serve you</p>
                    </div>`;
    }
    return;
   }

   itemsBox.innerHTML = '';
   data.items.forEach(item => {
    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-8 bg-white/5 rounded-3xl border border-white/5 animate-in slide-in-from-right-4 duration-500 group';
    row.innerHTML = `
                    <div class="flex items-center space-x-6">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-xl font-black group-hover:scale-110 transition-transform">
                            ${item.quantity}
                        </div>
                        <div>
                            <h4 class="text-xl font-black uppercase tracking-tight">${item.name}</h4>
                            <p class="text-sm font-bold text-white/40 mt-1">Rp${fmt.format(item.price)} per unit</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black italic tracking-tighter">Rp ${fmt.format(item.price * item.quantity)}</p>
                    </div>
                `;
    itemsBox.appendChild(row);
   });
  }

  setInterval(pollCart, 2000);
 </script>
</body>

</html>