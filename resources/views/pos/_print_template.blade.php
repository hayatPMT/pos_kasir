<div id="receipt-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
 <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
 <div class="relative min-h-screen flex items-center justify-center p-4">
  <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
   <!-- Receipt Content -->
   <div id="receipt-print-area" class="p-8 bg-white text-slate-900 font-mono text-[11px]">
    <div class="text-center space-y-2 mb-6">
     <h2 class="text-lg font-black tracking-tight uppercase">POS KASIR</h2>
     <p class="text-[9px] font-bold text-slate-500 tracking-widest uppercase">Global Enterprise • Headquarters</p>
     <div class="h-px bg-slate-100 w-full mt-4"></div>
    </div>

    <div class="space-y-1 mb-6 opacity-70">
     <div class="flex justify-between"><span>Inv:</span> <span id="print-invoice">#INV-0000</span></div>
     <div class="flex justify-between"><span>Date:</span> <span id="print-date">00/00/00 00:00</span></div>
     <div class="flex justify-between"><span>Cashier:</span> <span id="print-cashier">{{ Auth::user()->name }}</span></div>
    </div>

    <div class="border-y border-dashed border-slate-200 py-4 space-y-3 mb-6">
     <div id="print-items" class="space-y-3">
      <!-- Items inject here -->
     </div>
    </div>

    <div class="space-y-2 mb-6">
     <div class="flex justify-between font-bold"><span>Subtotal</span> <span id="print-subtotal">Rp 0</span></div>
     <div class="flex justify-between text-rose-600"><span>Adjustment</span> <span id="print-discount">-Rp 0</span></div>
     <div class="flex justify-between text-lg font-black pt-2 border-t border-slate-100">
      <span>TOTAL</span> <span id="print-total">Rp 0</span>
     </div>
    </div>

    <div class="space-y-1 mb-8 opacity-70 border-t border-slate-50 pt-4">
     <div class="flex justify-between"><span>Paid:</span> <span id="print-paid">Rp 0</span></div>
     <div class="flex justify-between"><span>Change:</span> <span id="print-change">Rp 0</span></div>
     <div class="flex justify-between"><span>Method:</span> <span id="print-method">CASH</span></div>
    </div>

    <div class="text-center space-y-2">
     <p class="font-black uppercase tracking-widest">Thank You</p>
     <p class="text-[9px] opacity-40 italic">Please keep this receipt for your records.</p>
    </div>
   </div>

   <!-- Modal Actions -->
   <div class="p-6 bg-slate-50 border-t border-slate-100 flex gap-3">
    <button onclick="closeReceiptModal()" class="flex-1 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Close</button>
    <button onclick="printReceipt()" class="flex-[2] bg-indigo-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all">Print Receipt</button>
   </div>
  </div>
 </div>
</div>

<style>
 @media print {
  body * {
   visibility: hidden;
  }

  #receipt-print-area,
  #receipt-print-area * {
   visibility: visible;
  }

  #receipt-print-area {
   position: absolute;
   left: 0;
   top: 0;
   width: 100%;
  }

  #receipt-modal {
   overflow: visible !important;
  }

  .fixed {
   position: relative !important;
  }

  .bg-slate-950\/80 {
   display: none !important;
  }
 }
</style>