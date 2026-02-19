@extends('layouts.app')

@section('title', 'Terminal POS')
@section('page_title', 'Point of Sale')

@section('content')
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
                    data-product="{{ e(json_encode($product)) }}"
                    onclick='addToCart(JSON.parse(this.dataset.product))'
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
                            <select name="member_id" id="member_select" onchange="updateMemberPoints()" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-100 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="">Guest/General</option>
                                @foreach ($members as $member)
                                <option value="{{ $member->id }}" data-points="{{ $member->points }}">{{ $member->name }} ({{ number_format($member->points) }} pts)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5" id="points-container" style="display: none;">
                            <label class="text-[9px] font-black text-rose-500 uppercase tracking-widest pl-1">Redeem Points</label>
                            <div class="relative">
                                <input type="number" name="points_redeemed" id="points_redeemed" value="0" min="0" oninput="calculateTotal()"
                                    class="w-full px-4 py-3 bg-rose-50 rounded-xl border border-rose-100 text-xs font-black text-rose-600 outline-none focus:ring-2 focus:ring-rose-500/20">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] font-black text-rose-400 uppercase">Max: <span id="max-points">0</span></span>
                            </div>
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

    function updateMemberPoints() {
        const select = document.getElementById('member_select');
        const container = document.getElementById('points-container');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            container.style.display = 'block';
            document.getElementById('max-points').innerText = selectedOption.dataset.points;
            document.getElementById('points_redeemed').max = selectedOption.dataset.points;
        } else {
            container.style.display = 'none';
            document.getElementById('points_redeemed').value = 0;
        }
        calculateTotal();
    }

    function calculateTotal() {
        const subtotal = cart.reduce((s, i) => s + (i.price * i.quantity), 0);

        // Point Redemption (1 point = Rp 100)
        const pts = parseInt(document.getElementById('points_redeemed').value) || 0;
        const pointDiscount = pts * 100;

        const total = Math.max(0, subtotal - pointDiscount);

        document.getElementById('display-subtotal').innerText = 'Rp ' + fmt.format(subtotal);
        if (pointDiscount > 0) {
            document.getElementById('display-subtotal').innerHTML += `<p class="text-[9px] text-rose-500 mt-1 uppercase font-black tracking-widest">- Rp${fmt.format(pointDiscount)} Points Redeemed</p>`;
        }

        document.getElementById('display-total').innerText = 'Rp ' + fmt.format(total);
        document.getElementById('display-total').dataset.val = total;

        const method = document.querySelector('select[name="payment_method"]').value;
        if (method === 'qris' || method === 'transfer') {
            document.getElementById('pay_amount').value = total;
        }
        calculateChange();
    }

    document.querySelector('select[name="payment_method"]').addEventListener('change', function(e) {
        if (e.target.value === 'qris' || e.target.value === 'transfer') {
            const total = document.getElementById('display-total').dataset.val;
            document.getElementById('pay_amount').value = total;
            calculateChange();
        }
    });

    async function syncWithDisplay(subtotal, total) {
        try {
            await fetch('{{ route("pos.display.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    items: cart,
                    subtotal: subtotal,
                    total: total,
                    status: cart.length > 0 ? 'active' : 'idle'
                })
            });
        } catch (err) {
            console.error('Display sync failed', err);
        }
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

    let currentQRISInvoice = null;
    let pollingInterval = null;

    let lastTransaction = null;

    function showReceiptModal(transaction) {
        lastTransaction = transaction;
        document.getElementById('receipt-modal').classList.remove('hidden');
        document.getElementById('print-invoice').innerText = '#' + transaction.invoice_number;
        document.getElementById('print-date').innerText = new Date(transaction.created_at).toLocaleString();
        document.getElementById('print-subtotal').innerText = 'Rp ' + fmt.format(transaction.subtotal);
        document.getElementById('print-discount').innerText = '-Rp ' + fmt.format(parseFloat(transaction.discount) + parseFloat(transaction.points_discount));
        document.getElementById('print-total').innerText = 'Rp ' + fmt.format(transaction.total);
        document.getElementById('print-paid').innerText = 'Rp ' + fmt.format(transaction.pay_amount);
        document.getElementById('print-change').innerText = 'Rp ' + fmt.format(transaction.change_amount);
        document.getElementById('print-method').innerText = transaction.payment_method.toUpperCase();

        const itemsBox = document.getElementById('print-items');
        itemsBox.innerHTML = '';
        transaction.details.forEach(it => {
            const row = document.createElement('div');
            row.className = 'flex justify-between items-start text-[10px]';
            row.innerHTML = `
                <div class="flex-1">
                    <p class="font-bold uppercase">${it.product.name}</p>
                    <p class="text-[8px] opacity-60">${it.quantity} x Rp${fmt.format(it.price)}</p>
                </div>
                <p class="font-bold">Rp${fmt.format(it.subtotal)}</p>
            `;
            itemsBox.appendChild(row);
        });
    }

    function shareWhatsApp() {
        if (!lastTransaction) return;
        const msg = `🧾 *RECEIPT: ${lastTransaction.invoice_number}*\n` +
            `Total: Rp ${fmt.format(lastTransaction.total)}\n` +
            `Items: ${lastTransaction.details.length} items purchased.\n\n` +
            `Thank you for shopping at ${window.location.hostname}`;

        window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
    }

    function showQRISModal(qris, transaction) {
        currentQRISInvoice = qris.invoice;
        document.getElementById('qris-total').innerText = 'Rp ' + fmt.format(qris.amount);
        document.getElementById('qris-image').src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qris.qr_string)}`;
        document.getElementById('qris-modal').classList.remove('hidden');

        // Start Polling
        startPolling();
    }

    function startPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(async () => {
            try {
                const resp = await fetch(`/pos/qris/status/${currentQRISInvoice}`);
                const res = await resp.json();
                if (res.status === 'completed') {
                    stopPolling();
                    document.getElementById('qris-modal').classList.add('hidden');
                    showToast('QRIS Payment Settled!');
                    showReceiptModal(res.data);
                }
            } catch (err) {
                console.error('Polling error', err);
            }
        }, 3000);
    }

    function stopPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
        pollingInterval = null;
    }

    async function simulateQRISSuccess() {
        try {
            await fetch('/pos/qris/simulate-success', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    invoice: currentQRISInvoice
                })
            });
            showToast('Simulating payment success...');
        } catch (err) {
            showToast('Simulation failed', 'error');
        }
    }

    function cancelQRIS() {
        if (confirm('Cancel this pending QRIS session?')) {
            stopPolling();
            document.getElementById('qris-modal').classList.add('hidden');
            showToast('Transaction Cancelled', 'error');
        }
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
        const method = formData.get('payment_method');

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
                if (method === 'qris') {
                    showQRISModal(res.qris, res.data);
                } else {
                    showToast('Transaction Successful');
                    showReceiptModal(res.data);
                }
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
@section('modals')
@include('pos._print_template')

<!-- QRIS Modal -->
<div id="qris-modal" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-500">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-8 border border-indigo-100 shadow-xl shadow-indigo-500/10">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m0 11v1m4-8h1m-11 0h1m3-4h.01M9 16h.01m1.99-7h.01M12 12h.01M15 12h.01M15 15h.01M12 18h.01M9 12h.01m5.99-7h.01M12 12h.01"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Scan QRIS</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8">Waiting for payment settlement...</p>

                <div class="aspect-square bg-slate-50 rounded-[2rem] border-4 border-slate-100 p-8 mb-8 relative group">
                    <img id="qris-image" src="" class="w-full h-full object-contain mix-blend-multiply opacity-80 group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-white/20 backdrop-blur-[2px] rounded-[2rem]">
                        <span class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl">Scan Active</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Payable Amount</p>
                        <p class="text-2xl font-black text-indigo-600 tracking-tighter" id="qris-total">Rp 0</p>
                    </div>

                    <button onclick="simulateQRISSuccess()" class="w-full py-4 text-slate-400 hover:text-indigo-600 text-[10px] font-black uppercase tracking-[0.2em] transition-all">Simulate Payment (Demo)</button>
                </div>
            </div>

            <button onclick="cancelQRIS()" class="w-full bg-slate-100 py-6 text-slate-500 font-black uppercase tracking-widest text-[10px] hover:bg-rose-50 hover:text-rose-500 transition-all">Cancel Transaction</button>
        </div>
    </div>
</div>
@endsection
@endsection