<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
      method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
      @csrf
      @if(isset($product)) @method('PUT') @endif

      <!-- Basic Info Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
        <div class="space-y-6">
          <h3 class="text-lg font-black text-slate-900 border-l-4 border-indigo-600 pl-4">Identification</h3>

          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Product Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
              class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
              placeholder="Enter product display name">
            @error('name') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">SKU Code</label>
              <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
                placeholder="PROD-001">
              @error('sku') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Barcode</label>
              <input type="text" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}"
                class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
                placeholder="Optional">
            </div>
          </div>

          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Branch Assignment</label>
            <select name="branch_id" required class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
              <option value="">Select Branch</option>
              @foreach ($branches as $branch)
              <option value="{{ $branch->id }}" {{ (old('branch_id', $product->branch_id ?? '') == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="text-lg font-black text-slate-900 border-l-4 border-amber-500 pl-4">Classification</h3>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Category</label>
              <select name="category_id" required class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                <option value="">Select</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Brand</label>
              <select name="brand_id" class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                <option value="">None</option>
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id ?? '') == $brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Product Image</label>
            <div class="relative group mt-2">
              <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
              <div class="w-full border-2 border-dashed border-slate-200 rounded-3xl p-8 flex flex-col items-center justify-center group-hover:bg-slate-50 group-hover:border-indigo-200 transition-all">
                <svg class="w-10 h-10 text-slate-300 mb-2 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-tighter group-hover:text-indigo-600">Click or drag to upload</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pricing & Inventory Section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 pt-10 border-t border-slate-50">
        <div class="space-y-6">
          <h3 class="text-lg font-black text-slate-900 border-l-4 border-emerald-500 pl-4">Pricing</h3>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Buy Price</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-slate-400">Rp</span>
                <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price ?? 0) }}" required
                  class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
              </div>
            </div>
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Sell Price</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-slate-400">Rp</span>
                <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price ?? 0) }}" required
                  class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Member Price</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-slate-400">Rp</span>
                <input type="number" name="member_price" value="{{ old('member_price', $product->member_price ?? 0) }}"
                  class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Optional">
              </div>
            </div>
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Wholesale Price</label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center font-bold text-slate-400">Rp</span>
                <input type="number" name="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price ?? 0) }}"
                  class="w-full pl-11 pr-4 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-purple-500 outline-none transition-all" placeholder="Optional">
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <h3 class="text-lg font-black text-slate-900 border-l-4 border-rose-500 pl-4">Inventory</h3>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Initial Stock</label>
              <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
                class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-rose-500 outline-none transition-all">
            </div>
            <div>
              <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Alert Threshold</label>
              <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? 10) }}" required
                class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-rose-500 outline-none transition-all">
            </div>
          </div>
        </div>
      </div>

      <div class="pt-10 flex items-center space-x-4 border-t border-slate-50">
        <button type="submit"
          class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-4 rounded-2xl transition-all active:scale-[0.98] shadow-2xl shadow-slate-900/10 text-lg uppercase tracking-widest">
          {{ isset($product) ? 'Save Updates' : 'Create Product' }}
        </button>
        <a href="{{ route('products.index') }}"
          class="bg-slate-100 hover:bg-slate-200 text-slate-500 font-black px-10 py-4 rounded-2xl transition-all uppercase tracking-widest text-sm text-center">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>