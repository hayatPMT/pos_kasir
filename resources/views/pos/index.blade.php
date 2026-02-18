@extends('layouts.app')

@section('title', 'Terminal POS')
@section('page_title', 'Point of Sale')

@section('content')
@include('pos._print_template')
<div class="h-[calc(100vh-12rem)] flex flex-col lg:flex-row gap-6">
    <!-- LEFT: PRODUCT CATALOG -->
    <div class="flex-1 min-w-0 flex flex-col bg-white rounded-[2.5rem] shadow-premium border border-slate-100 overflow-hidden">
        <!-- Header: Search & Category -->
        <div class="p-6 border-b border-slate-50 bg-slate-50/20">
            <form action="{{ route('pos.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1 group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-12 pr-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all bg-white font-bold text-slate-700"
                        placeholder="Search our collection...">
                </div>
                <select name="category" onchange="this.form.submit()"
                    class="sm:w-56 px-6 py-4 rounded-2xl border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 bg-white font-bold text-slate-700 appearance-none cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Catalog Grid -->
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse ($products as $product)
                <button type="button"
                    onclick="addToCart({{ json_encode($product) }})"
                    class="group bg-white rounded-3xl border border-slate-100 p-6 text-left transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5 hover:border-indigo-100 active:scale-[0.98] flex flex-col min-h-[300px] relative">

                    @if ($product->stock <= $product->min_stock)
                        <div class="absolute top-4 right-4 px-2 py-1 bg-rose-500 text-white text-[9px] font-black uppercase rounded-lg shadow-lg z-10">Low Stock</div>
                        @endif

                        <div class="aspect-square bg-slate-50 rounded-2xl mb-5 flex items-center justify-center overflow-hidden border border-slate-100 relative">
                            @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                            <svg class="w-12 h-12 text-slate-200 group-hover:text-indigo-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            @endif
                        </div>

                        <div class="flex-1 mb-4">
                            <h4 class="font-black text-slate-900 text-base leading-tight mb-1 truncate uppercase">{{ $product->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $product->sku }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <span class="text-lg font-black text-indigo-600">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase bg-slate-50 px-2 py-1 rounded-lg">Stock: {{ $product->stock }}</span>
                        </div>
                </button>
                @empty
                <div class="col-span-full py-20 text-center opacity-30">
                    <p class="font-black uppercase tracking-widest text-xs">No catalog items available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RIGHT: CHECKOUT CART -->
    <div class="w-full lg:w-[420px] flex flex-col bg-white rounded-[2.5rem] shadow-premium border border-slate-100 overflow-hidden">
        <!-- Cart Header -->
        <div class="p-8 bg-slate-950 text-white">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-xl font-black tracking-tight">Orders Checkout</h3>
                <button onclick="clearCart()" class="p-2 text-white/30 hover:text-rose-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <p class="text-[9px] font-bold text-white/20 uppercase tracking-[0.25em]">Transaction Monitoring</p>
        </div>

        <!-- Selected Items List -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" id="cart-container">
            <!-- Items injected by JS -->
            <div id="empty-cart" class="h-full flex flex-col items-center justify-center py-10 opacity-20">
                <svg class="w-12 h-12 mb-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-[9px] font-black uppercase tracking-widest text-center">Your basket is empty</p>
            </div>
        </div>

        <!-- Totals & Actions -->
        <div class="p-8 bg-slate-50/50 border-t border-slate-100">
            <form id="checkout-form" class="space-y-6">
                @csrf
                <div id="hidden-inputs"></div>

                <div class="space-y-4 mb-8">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Membership</label>
                            <select name="member_id" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-100 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="">Guest/General</option>
                                @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Payment</label>
                            <select name="payment_method" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-100 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="cash">Cash</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-200 pt-6 space-y-3 mb-8">
                    <div class="flex justify-between items-center text-slate-500">
                        <span class="text-[10px] font-black uppercase tracking-widest">Subtotal</span>
                        <span class="text-sm font-black" id="display-subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                        <span class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Total Due</span>
                        <span class="text-3xl font-black text-indigo-600 tracking-tighter" id="display-total" data-val="0">Rp 0</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Amount Paid (Rp)</label>
                        <input type="number" name="pay_amount" id="pay_amount" oninput="calculateChange()" required
                            class="w-full px-6 py-4 rounded-xl bg-indigo-50/30 border border-indigo-100 text-xl font-black text-indigo-900 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 outline-none"
                            placeholder="0">
                    </div>

                    <div class="flex justify-between items-center p-4 bg-emerald-500 text-white rounded-xl">
                        <span class="text-[10px] font-black uppercase tracking-widest">Change Due</span>
                        <span class="text-lg font-black" id="display-change">Rp 0</span>
                    </div>

                    <button type="submit" class="w-full bg-slate-950 text-white py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center justify-center gap-3">
                        Finalize Order
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    const fmt = new Intl.NumberFormat('id-ID');

    function addToCart(product) {
        const item = cart.find(i => i.id === product.id);
        if (item) {
            if (item.quantity < product.stock) item.quantity++;
            else showToast('Stock Limit reached', 'error');
        } else {
            if (product.stock > 0) cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.sell_price),
                quantity: 1,
                stock: product.stock
            });
            else showToast('Out of Stock', 'error');
        }
        renderCart();
    }

    function updateQuantity(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        if (item.quantity + delta > item.stock) {
            showToast('Insufficient Stock', 'error');
            return;
        }
        item.quantity += delta;
        if (item.quantity <= 0) cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        if (confirm('Clear current basket?')) {
            cart = [];
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        const hidden = document.getElementById('hidden-inputs');
        container.innerHTML = '';
        hidden.innerHTML = '';

        if (cart.length === 0) {
            container.innerHTML = `<div id="empty-cart" class="h-full flex flex-col items-center justify-center py-10 opacity-20">
                <svg class="w-12 h-12 mb-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <p class="text-[9px] font-black uppercase tracking-widest text-center">Your basket is empty</p>
            </div>`;
            calculateTotal();
            return;
        }

        cart.forEach((item, idx) => {
            const el = document.createElement('div');
            el.className = 'flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm animate-in slide-in-from-right-2';
            el.innerHTML = `
                <div class="flex-1 min-w-0">
                    <h5 class="text-[11px] font-black text-slate-800 uppercase truncate">${item.name}</h5>
                    <p class="text-[10px] font-bold text-indigo-600 uppercase mt-0.5">Rp${fmt.format(item.price)}</p>
                </div>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-lg border border-slate-100">
                    <button type="button" onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center bg-white rounded-md text-slate-400 hover:text-rose-500 shadow-sm transition-colors">-</button>
                    <span class="text-[11px] font-black text-slate-900 w-4 text-center">${item.quantity}</span>
                    <button type="button" onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center bg-white rounded-md text-slate-400 hover:text-indigo-600 shadow-sm transition-colors">+</button>
                </div>
            `;
            container.appendChild(el);
            hidden.innerHTML += `<input type="hidden" name="items[${idx}][product_id]" value="${item.id}"><input type="hidden" name="items[${idx}][quantity]" value="${item.quantity}">`;
        });
        calculateTotal();
    }

    function calculateTotal() {
        const subtotal = cart.reduce((s, i) => s + (i.price * i.quantity), 0);
        document.getElementById('display-subtotal').innerText = 'Rp ' + fmt.format(subtotal);
        document.getElementById('display-total').innerText = 'Rp ' + fmt.format(subtotal);
        document.getElementById('display-total').dataset.val = subtotal;
        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat(document.getElementById('display-total').dataset.val || 0);
        const pay = parseFloat(document.getElementById('pay_amount').value) || 0;
        document.getElementById('display-change').innerText = 'Rp ' + fmt.format(Math.max(0, pay - total));
    }

    function showToast(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = `fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-2xl z-[100] ${type==='success'?'bg-emerald-500/90':'bg-rose-500/90'} text-white backdrop-blur-md animate-in slide-in-from-bottom-2`;
        t.innerText = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

    // Modal Receipt Logic
    function showReceiptModal(data) {
        document.getElementById('print-invoice').innerText = '#' + data.invoice_number;
        document.getElementById('print-date').innerText = new Date(data.created_at).toLocaleString('id-ID');
        document.getElementById('print-subtotal').innerText = 'Rp ' + fmt.format(data.subtotal);
        document.getElementById('print-total').innerText = 'Rp ' + fmt.format(data.total);
        document.getElementById('print-paid').innerText = 'Rp ' + fmt.format(data.pay_amount);
        document.getElementById('print-change').innerText = 'Rp ' + fmt.format(data.change_amount);
        document.getElementById('print-method').innerText = data.payment_method || 'CASH';

        const itemsBox = document.getElementById('print-items');
        itemsBox.innerHTML = '';
        data.details.forEach(it => {
            const row = document.createElement('div');
            row.className = 'flex justify-between items-start';
            row.innerHTML = `
                <div class="flex-1">
                    <p class="font-bold uppercase">${it.product.name}</p>
                    <p class="text-[8px] opacity-60">${it.quantity} x Rp${fmt.format(it.price)}</p>
                </div>
                <p class="font-bold">Rp${fmt.format(it.subtotal)}</p>
            `;
            itemsBox.appendChild(row);
        });

        document.getElementById('receipt-modal').classList.remove('hidden');
    }

    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
        cart = [];
        document.getElementById('pay_amount').value = '';
        renderCart();
    }

    function printReceipt() {
        window.print();
    }

    // Standard AJAX Form Handling
    document.getElementById('checkout-form').onsubmit = async (e) => {
        e.preventDefault();
        if (cart.length === 0) {
            showToast('Cart is empty', 'error');
            return;
        }

        const formData = new FormData(e.target);

        try {
            const resp = await fetch('{{ route("pos.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const res = await resp.json();
            if (res.success) {
                showToast('Transaction Successful');
                showReceiptModal(res.data);
            } else {
                showToast(res.message || 'Transaction Failed', 'error');
            }
        } catch (err) {
            showToast('System Error', 'error');
        }
    };
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 99px;
    }

    .shadow-premium {
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection