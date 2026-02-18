<div class="max-w-4xl mx-auto">
 <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
  <form action="{{ isset($branch) ? route('branches.update', $branch->id) : route('branches.store') }}"
   method="POST" class="p-10 space-y-10">
   @csrf
   @if(isset($branch)) @method('PUT') @endif

   <!-- Basic Info Section -->
   <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
    <div class="space-y-6">
     <h3 class="text-lg font-black text-slate-900 border-l-4 border-indigo-600 pl-4">Location Info</h3>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Branch Name</label>
      <input type="text" name="name" value="{{ old('name', $branch->name ?? '') }}" required
       class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
       placeholder="Full Name">
      @error('name') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Branch Code</label>
      <input type="text" name="code" value="{{ old('code', $branch->code ?? '') }}" required
       class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
       placeholder="Example: BR-001">
      @error('code') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Active Status</label>
      <select name="is_active" class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
       <option value="1" {{ old('is_active', $branch->is_active ?? '1') == '1' ? 'selected' : '' }}>Active</option>
       <option value="0" {{ old('is_active', $branch->is_active ?? '1') == '0' ? 'selected' : '' }}>Inactive</option>
      </select>
      @error('is_active') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>
    </div>

    <div class="space-y-6">
     <h3 class="text-lg font-black text-slate-900 border-l-4 border-amber-500 pl-4">Contact Details</h3>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
      <input type="text" name="phone" value="{{ old('phone', $branch->phone ?? '') }}"
       class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
       placeholder="Example: 08123456789">
      @error('phone') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
      <input type="email" name="email" value="{{ old('email', $branch->email ?? '') }}"
       class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300"
       placeholder="Optional">
      @error('email') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>

     <div>
      <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Address</label>
      <textarea name="address" rows="4"
       class="w-full px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-semibold focus:ring-2 focus:ring-indigo-500 outline-none transition-all placeholder-slate-300 resize-none"
       placeholder="Full Address (Optional)">{{ old('address', $branch->address ?? '') }}</textarea>
      @error('address') <p class="mt-1 text-xs text-rose-500 font-bold tracking-tight">{{ $message }}</p> @enderror
     </div>
    </div>
   </div>

   <div class="pt-10 flex items-center space-x-4 border-t border-slate-50">
    <button type="submit"
     class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-4 rounded-2xl transition-all active:scale-[0.98] shadow-2xl shadow-slate-900/10 text-lg uppercase tracking-widest">
     {{ isset($branch) ? 'Save Updates' : 'Add Branch' }}
    </button>
    <a href="{{ route('branches.index') }}"
     class="bg-slate-100 hover:bg-slate-200 text-slate-500 font-black px-10 py-4 rounded-2xl transition-all uppercase tracking-widest text-sm text-center">
     Cancel
    </a>
   </div>
  </form>
 </div>
</div>