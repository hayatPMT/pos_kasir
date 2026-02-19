@extends('layouts.app')

@section('title', 'Self-Service Kiosk')
@section('page_title', 'Autonomous Terminal')

@section('content')
<div class="h-[calc(100vh-12rem)] flex flex-col gap-8">
    <!-- Category Strip -->
    <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
        <button onclick="filterKiosk('all')" class="px-8 py-5 bg-slate-950 text-white rounded-3xl font-black text-xs uppercase tracking-widest whitespace-nowrap shadow-xl">All Assets</button>
        @foreach($categories as $cat)
        <button onclick="filterKiosk('{{ $cat->slug }}')" class="px-8 py-5 bg-white text-slate-400 rounded-3xl font-black text-xs uppercase tracking-widest whitespace-nowrap border border-slate-100 hover:text-indigo-600 transition-all">{{ $cat->name }}</button>
        @endforeach
    </div>

    <div class="flex-1 grid grid-cols-12 gap-8 overflow-hidden">
        <!-- Asset Grid -->
        <div class="col-span-12 lg:col-span-8 overflow-y-auto pr-4 custom-scrollbar" id="kiosk-grid">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $p)
                <div class="kiosk-item premium-card p-8 group hover:bg-slate-950 hover:text-white transition-all duration-500 cursor-pointer active:scale-95"
                    data-category="{{ $p->category->slug ?? '' }}"
                    data-product="{{ e(json_encode($p)) }}"
                    onclick="addToKioskCart(JSON.parse(this.dataset.product))">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl mb-6 flex items-center justify-center group-hover:bg-white/10 transition-colors">
                        <svg class="w-8 h-8 text-slate-300 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-black uppercase tracking-tight mb-2">{{ $p->name }}</h4>
                    <div class="flex justify-between items-center mt-auto">
                        <span class="text-xs font-bold text-indigo-600 group-hover:text-white">Rp{{ number_format($p->sell_price, 0, ',', '.') }}</span>
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-30">{{ $p->stock }} Remaining</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary (Floating Panel) -->
        <div class="col-span-12 lg:col-span-4 bg-white rounded-[3rem] shadow-2xl border border-slate-100 p-10 flex flex-col">
            <div class="flex justify-between items-center mb-10">
                <h4 class="text-xl font-black text-slate-900 tracking-tight uppercase">Your Selection</h4>
                <button onclick="clearKiosk()" class="text-[10px] font-black text-rose-500 uppercase tracking-widest">Reset</button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-6 mb-10 pr-2 custom-scrollbar" id="kiosk-cart">
                <div class="h-full flex flex-col items-center justify-center opacity-10 py-20">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="font-black uppercase tracking-widest text-xs">Waiting for choice</p>
                </div>
            </div>

            <div class="space-y-6 border-t border-slate-50 pt-10">
                <div class="flex justify-between items-center text-slate-400">
                    <span class="text-[10px] font-black uppercase tracking-widest">Total Payable</span>
                    <span class="text-2xl font-black text-slate-900 tracking-tighter" id="kiosk-total">Rp 0</span>
                </div>
                <button onclick="checkoutKiosk()" class="w-full bg-indigo-600 text-white py-6 rounded-3xl font-black uppercase tracking-[0.2em] text-xs shadow-2xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all flex items-center justify-center gap-3 active:scale-95">
                    Proceed to Payment
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let kioskCart = [];
    const fmt = new Intl.NumberFormat('id-ID');

    function addToKioskCart(product) {
        const item = kioskCart.find(i => i.id === product.id);
        if (item) {
            if (item.quantity < product.stock) item.quantity++;
            else alert('Limit reached');
        } else {
            kioskCart.push({
                ...product,
                quantity: 1
            });
        }
        renderKioskCart();
    }

    function renderKioskCart() {
        const container = document.getElementById('kiosk-cart');
        container.innerHTML = '';

        if (kioskCart.length === 0) {
            container.innerHTML = `<div class="h-full flex flex-col items-center justify-center opacity-10 py-20">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="font-black uppercase tracking-widest text-xs">Waiting for choice</p>
                </div>`;
            document.getElementById('kiosk-total').innerText = 'Rp 0';
            return;
        }

        let total = 0;
        kioskCart.forEach((item, idx) => {
            total += item.sell_price * item.quantity;
            const el = document.createElement('div');
            el.className = 'flex items-center gap-6 animate-in slide-in-from-right-4 transition-all';
            el.innerHTML = `
                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center font-black text-indigo-600">${item.quantity}x</div>
                <div class="flex-1">
                    <p class="text-[11px] font-black uppercase tracking-tight">${item.name}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-0.5">Rp${fmt.format(item.sell_price * item.quantity)}</p>
                </div>
                <button onclick="removeFromKiosk(${item.id})" class="p-2 text-rose-500 opacity-20 hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            container.appendChild(el);
        });
        document.getElementById('kiosk-total').innerText = 'Rp ' + fmt.format(total);
    }

    function removeFromKiosk(id) {
        kioskCart = kioskCart.filter(i => i.id !== id);
        renderKioskCart();
    }

    function clearKiosk() {
        if (confirm('Clear entire selection?')) {
            kioskCart = [];
            renderKioskCart();
        }
    }

    function filterKiosk(slug) {
        const items = document.querySelectorAll('.kiosk-item');
        items.forEach(it => {
            if (slug === 'all' || it.dataset.category === slug) {
                it.style.display = 'block';
            } else {
                it.style.display = 'none';
            }
        });
    }

    function checkoutKiosk() {
        if (kioskCart.length === 0) return;
        alert('Self-checkout sequence initiated. Please proceed to payment terminal.');
        // In a real app, this would redirect to a specific checkout page/payment link
    }
</script>
@endsection