@extends('layouts.app')

@section('title', 'New Purchase Order')
@section('page_title', 'Strategic Procurement')

@section('content')
<div class="max-w-5xl mx-auto">
 <form action="{{ route('purchase-orders.store') }}" method="POST" class="space-y-8">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
   <!-- Left: Document Context -->
   <div class="lg:col-span-1 space-y-6">
    <div class="premium-card p-8 space-y-6">
     <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Core Context</h4>

     <div class="space-y-2">
      <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Partner Entity</label>
      <select name="supplier_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
       <option value="">Select Primary Supplier</option>
       @foreach($suppliers as $supplier)
       <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
       @endforeach
      </select>
     </div>

     <div class="space-y-2">
      <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Destination Node</label>
      <select name="branch_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
       @foreach($branches as $branch)
       <option value="{{ $branch->id }}" {{ Auth::user()->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
       @endforeach
      </select>
     </div>

     <div class="space-y-2">
      <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Authorized Date</label>
      <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500">
     </div>

     <div class="space-y-2">
      <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Strategic Notes</label>
      <textarea name="notes" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500" placeholder="Operational rationale..."></textarea>
     </div>
    </div>

    <div class="premium-card p-6 bg-slate-950 text-white border-none">
     <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] mb-4">Financial Summary</p>
     <div class="flex justify-between items-center">
      <span class="text-xs font-bold opacity-60">Total Estimated Value</span>
      <span class="text-xl font-black tracking-tight" id="summary-total">Rp 0</span>
     </div>
    </div>

    <button type="submit" class="w-full bg-indigo-600 text-white py-6 rounded-3xl font-black uppercase tracking-widest text-[11px] shadow-2xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all">
     Authorize Procurement
    </button>
   </div>

   <!-- Right: Item Matrix -->
   <div class="lg:col-span-2 space-y-6">
    <div class="premium-card overflow-hidden">
     <div class="p-8 border-b border-slate-50 flex justify-between items-center">
      <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">Inventory Matrix</h4>
      <button type="button" onclick="addItem()" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">Add Inventory Item</button>
     </div>

     <div class="p-4 overflow-y-auto max-h-[600px] custom-scrollbar" id="items-container">
      <!-- Dynamic rows here -->
     </div>

     @if($products->isEmpty())
     <div class="p-20 text-center opacity-20">
      <p class="text-[10px] font-black uppercase tracking-widest">No assets available for procurement</p>
     </div>
     @endif
    </div>
   </div>
  </div>
 </form>
</div>

<!-- Template Row -->
<template id="item-row-template">
 <div class="item-row p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col md:flex-row gap-6 mb-4 relative transition-all animate-in slide-in-from-right-4">
  <div class="flex-1 space-y-2">
   <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Global Product</label>
   <select name="items[INDEX][product_id]" required onchange="updateRow(this)" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl text-[11px] font-black outline-none focus:ring-4 focus:ring-indigo-500/10">
    <option value="">Select Asset</option>
    @foreach($products as $p)
    <option value="{{ $p->id }}" data-price="{{ $p->buy_price }}">{{ $p->name }} ({{ $p->sku }})</option>
    @endforeach
   </select>
  </div>
  <div class="w-full md:w-32 space-y-2">
   <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Quantity</label>
   <input type="number" name="items[INDEX][quantity]" value="1" min="1" required oninput="calculateRow(this)" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl text-[11px] font-black outline-none">
  </div>
  <div class="w-full md:w-44 space-y-2">
   <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Unit Cost (Rp)</label>
   <input type="number" name="items[INDEX][cost_price]" step="0.01" required oninput="calculateRow(this)" class="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl text-[11px] font-black outline-none placeholder:opacity-20" placeholder="0.00">
  </div>
  <div class="w-full md:w-44 space-y-2">
   <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Subtotal (Estimated)</label>
   <div class="px-4 py-3 bg-indigo-50/50 rounded-xl text-[11px] font-black text-indigo-600 row-total">Rp 0</div>
  </div>
  <button type="button" onclick="removeItem(this)" class="absolute -top-2 -right-2 p-2 bg-rose-500 text-white rounded-lg shadow-lg hover:rotate-90 transition-all opacity-0 group-hover:opacity-100">
   <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
   </svg>
  </button>
 </div>
</template>

<script>
 let itemIndex = 0;
 const fmt = new Intl.NumberFormat('id-ID');

 function addItem() {
  const template = document.getElementById('item-row-template');
  const container = document.getElementById('items-container');
  const clone = template.content.cloneNode(true);

  // Replace INDEX with current counter
  const inputs = clone.querySelectorAll('[name*="INDEX"]');
  inputs.forEach(input => {
   input.name = input.name.replace('INDEX', itemIndex);
  });

  container.appendChild(clone);
  itemIndex++;

  // If it's the first item, try to find a product to default to or just let user pick
  updateGlobalTotal();
 }

 function removeItem(btn) {
  btn.closest('.item-row').remove();
  updateGlobalTotal();
 }

 function updateRow(select) {
  const row = select.closest('.item-row');
  const selectedOption = select.options[select.selectedIndex];
  const price = selectedOption.dataset.price || 0;

  const priceInput = row.querySelector('[name*="cost_price"]');
  priceInput.value = price;

  calculateRow(priceInput);
 }

 function calculateRow(input) {
  const row = input.closest('.item-row');
  const qty = parseFloat(row.querySelector('[name*="quantity"]').value) || 0;
  const price = parseFloat(row.querySelector('[name*="cost_price"]').value) || 0;
  const total = qty * price;

  row.querySelector('.row-total').innerText = 'Rp ' + fmt.format(total);
  row.dataset.total = total;

  updateGlobalTotal();
 }

 function updateGlobalTotal() {
  let globalTotal = 0;
  document.querySelectorAll('.item-row').forEach(row => {
   globalTotal += parseFloat(row.dataset.total || 0);
  });

  document.getElementById('summary-total').innerText = 'Rp ' + fmt.format(globalTotal);
 }

 // Initialize with one item
 addItem();
</script>

<style>
 .item-row:hover .absolute {
  opacity: 1;
 }
</style>
@endsection