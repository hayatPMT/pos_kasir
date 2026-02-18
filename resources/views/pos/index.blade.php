@extends('layouts.app')

@section('title', 'Cashier POS')
@section('page_title', 'Point of Sale')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 h-[calc(100vh-14rem)]">
    <!-- Left Side: Products -->
    <div class="flex-1 flex flex-col min-w-0 premium-card overflow-hidden">
        <!-- Search & Filter -->
        <div class="p-8 border-b border-slate-100 bg-slate-50/30 space-y-4">
            <form action="{{ route('pos.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1 group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-14 pr-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all bg-white font-medium text-slate-700"
                        placeholder="Scan barcode or search products...">
                </div>
                <select name="category" onchange="this.form.submit()"
                    class="md:w-60 px-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 bg-white font-bold text-slate-700 appearance-none cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                @forelse ($products as $product)
                <button type="button"
                    onclick='addToCart(@json($product))'
                    class="group bg-white rounded-3xl border border-slate-100 p-5 text-left transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10 hover:border-indigo-100 active:scale-[0.97] relative overflow-hidden flex flex-col">

                    @if ($product->stock <= $product->min_stock)
                        <div class="absolute top-3 right-3 px-2 py-1 bg-rose-500 text-white text-[8px] font-black uppercase rounded-md shadow-lg shadow-rose-500/40 z-10 animate-pulse">Low Stock</div>
                        @endif

                        <div class="aspect-square bg-slate-50 rounded-2xl mb-5 flex items-center justify-center overflow-hidden border border-slate-100/50 relative">
                            @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                <svg class="w-8 h-8 text-slate-200 group-hover:text-indigo-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            @endif
                            <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/5 transition-colors duration-300"></div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 text-sm truncate group-hover:text-indigo-600 transition-colors mb-1">{{ $product->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-extrabold mb-4 uppercase tracking-tighter">{{ $product->sku }}</p>
                        </div>

                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                            <span class="text-sm font-black text-indigo-600 leading-none">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            <span class="text-[9px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-lg uppercase tracking-wider">{{ $product->stock }} in stock</span>
                        </div>
                </button>
                @empty
                <div class="col-span-full py-32 text-center opacity-40">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-black uppercase tracking-[0.2em] text-xs">No matching catalog items</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Side: Cart -->
    <div class="w-full lg:w-[450px] flex flex-col glass-card !rounded-[2.5rem] !border-white !shadow-2xl !shadow-slate-300/40 overflow-hidden">
        <div class="p-8 border-b border-slate-100 bg-slate-950 text-white">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xl font-black tracking-tight">Orders Checkout</h3>
                <button onclick="clearCart()" class="p-2 text-white/40 hover:text-rose-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Live Transaction Basket</p>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-8 space-y-4 custom-scrollbar" id="cart-container">
            <div id="empty-cart" class="h-full flex flex-col items-center justify-center py-20 opacity-20 group">
                <div class="w-32 h-32 bg-slate-100 rounded-full flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-700">
                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <p class="font-black uppercase tracking-[0.3em] text-[10px]">Your basket is currently empty</p>
            </div>
            <!-- Items injected here -->
        </div>

        <!-- Checkout Area -->
        <div class="p-8 bg-slate-50/50 backdrop-blur-md border-t border-slate-100">
            <form action="{{ route('pos.store') }}" method="POST" id="checkout-form">
                @csrf
                <div id="hidden-inputs"></div>

                <div class="space-y-4 mb-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-[9px] uppercase font-black text-slate-400 mb-2 tracking-[0.1em]">Membership</label>
                            <select name="member_id" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-200 text-xs font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer">
                                <option value="">Non-Member/Guest</option>
                                @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] uppercase font-black text-slate-400 mb-2 tracking-[0.1em]">Payment Mode</label>
                            <select name="payment_method" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-200 text-xs font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer">
                                <option value="cash">Cash Payment</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="qris">QRIS Digital</option>
                                <option value="e-wallet">E-Wallet</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase font-black text-slate-400 mb-2 tracking-[0.1em]">Active Campaign</label>
                        <select name="promotion_id" id="promotion_id" onchange="calculateTotal()" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-200 text-xs font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer">
                            <option value="" data-value="0" data-type="nominal">No Discount Applied</option>
                            @foreach ($promotions as $promo)
                            <option value="{{ $promo->id }}" data-value="{{ $promo->value }}" data-type="{{ $promo->type }}">{{ $promo->name }} ({{ $promo->type == 'percentage' ? $promo->value.'%' : 'Flat' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-200 pt-6 space-y-3 mb-8">
                    <div class="flex justify-between items-center text-slate-500">
                        <span class="text-xs font-bold uppercase tracking-wider">Subtotal</span>
                        <span class="font-black" id="display-subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-rose-500">
                        <span class="text-xs font-bold uppercase tracking-wider">Adjustment</span>
                        <span class="font-black" id="display-discount">-Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="text-sm font-black text-slate-900 uppercase tracking-widest leading-none">Total Payable</span>
                        <span class="text-3xl font-black text-indigo-600 tracking-tighter" id="display-total">Rp 0</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-[9px] uppercase font-black text-slate-400 mb-2 block tracking-[0.1em]">Received Amount (Rp)</label>
                        <input type="number" name="pay_amount" id="pay_amount" oninput="calculateChange()" required
                            class="w-full px-8 py-5 rounded-[1.5rem] bg-indigo-50/50 border-2 border-indigo-100 text-2xl font-black text-indigo-900 focus:border-indigo-600 focus:bg-white outline-none transition-all placeholder-indigo-200 shadow-inner"
                            placeholder="0">
                    </div>

                    <div class="flex justify-between items-center px-6 py-4 bg-emerald-500 text-white rounded-2xl shadow-xl shadow-emerald-500/20 border border-emerald-400">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Balance Change</span>
                        <span class="text-xl font-black" id="display-change">Rp 0</span>
                    </div>

                    <button type="submit"
                        class="w-full bg-slate-950 hover:bg-slate-900 text-white font-black py-6 rounded-[1.5rem] transition-all duration-300 active:scale-[0.98] shadow-2xl shadow-slate-950/20 text-lg uppercase tracking-[0.1em] flex items-center justify-center gap-4 group">
                        <span>Finalize Transaction</span>
                        <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let cart = [];

    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);

        if (existingItem) {
            if (existingItem.quantity < product.stock) {
                existingItem.quantity++;
            } else {
                showToast('Stock limit reached!', 'error');
                return;
            }
        } else {
            if (product.stock > 0) {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: parseFloat(product.sell_price),
                    quantity: 1,
                    stock: product.stock
                });
            } else {
                showToast('Out of stock!', 'error');
                return;
            }
        }
        renderCart();
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        renderCart();
    }

    function updateQuantity(productId, delta) {
        const item = cart.find(i => i.id === productId);
        if (!item) return;

        const newQty = item.quantity + delta;

        if (newQty > item.stock) {
            showToast('Insufficient stock!', 'error');
            return;
        }

        if (newQty <= 0) {
            removeFromCart(productId);
            return;
        }

        item.quantity = newQty;
        renderCart();
    }

    function clearCart() {
        if (cart.length > 0 && confirm('Wipe the current basket?')) {
            cart = [];
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        const hiddenInputs = document.getElementById('hidden-inputs');
        const emptyState = document.getElementById('empty-cart');

        container.innerHTML = '';
        hiddenInputs.innerHTML = '';

        if (cart.length === 0) {
            container.appendChild(emptyState.cloneNode(true));
            calculateTotal();
            return;
        }

        cart.forEach((item, index) => {
            const itemEl = document.createElement('div');
            itemEl.className = 'flex items-center justify-between p-5 bg-white rounded-2xl border border-slate-100 shadow-sm animate-in fade-in zoom-in duration-300 transition-all';
            itemEl.innerHTML = `
                <div class="min-w-0 pr-4">
                    <h5 class="font-black text-slate-800 text-sm truncate uppercase tracking-tight">${item.name}</h5>
                    <p class="text-[10px] font-black text-indigo-600 tracking-wider">Rp${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-slate-50 rounded-xl border border-slate-100 p-1">
                        <button type="button" onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors font-black">-</button>
                        <span class="w-8 text-center text-xs font-black text-indigo-600">${item.quantity}</span>
                        <button type="button" onclick="updateQuantity(${item.id}, 1)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors font-black">+</button>
                    </div>
                </div>
            `;
            container.appendChild(itemEl);

            hiddenInputs.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        calculateTotal();
    }

    function calculateTotal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const promoSelect = document.getElementById('promotion_id');
        const selectedPromo = promoSelect.options[promoSelect.selectedIndex];
        let discount = 0;

        if (selectedPromo && selectedPromo.value) {
            const promoValue = parseFloat(selectedPromo.dataset.value) || 0;
            const promoType = selectedPromo.dataset.type;
            discount = promoType === 'percentage' ? (subtotal * promoValue) / 100 : promoValue;
        }

        const total = Math.max(0, subtotal - discount);

        document.getElementById('display-subtotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        document.getElementById('display-discount').innerText = '-Rp ' + new Intl.NumberFormat('id-ID').format(discount);
        document.getElementById('display-total').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('display-total').dataset.rawValue = total;

        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat(document.getElementById('display-total').dataset.rawValue || 0);
        const payAmount = parseFloat(document.getElementById('pay_amount').value) || 0;
        const change = Math.max(0, payAmount - total);
        document.getElementById('display-change').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-10 left-1/2 -translate-x-1/2 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl z-[100] animate-in fade-in slide-in-from-bottom-5 duration-500
            ${type === 'success' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'}`;
        toast.innerText = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-out', 'fade-out', 'slide-out-to-bottom-5');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        /* bg-slate-200 */
        border-radius: 9999px;
        transition: background-color 0.2s;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #e0e7ff;
        /* bg-indigo-100 */
    }
</style>
@endsection