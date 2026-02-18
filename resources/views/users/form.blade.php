<form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST" class="p-12 space-y-12">
 @csrf
 @if(isset($user)) @method('PUT') @endif

 <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-10">
  <!-- Name -->
  <div class="flex flex-col">
   <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Personnel Full Name</label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
     </svg>
    </span>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required
     class="input-premium pl-14" placeholder="e.g. Alexander Hamilton">
   </div>
   @error('name') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
  </div>

  <!-- Email -->
  <div class="flex flex-col">
   <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Access Credential (Email)</label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
     </svg>
    </span>
    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
     class="input-premium pl-14" placeholder="staff@enterprise.com">
   </div>
   @error('email') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
  </div>

  <!-- Role -->
  <div class="flex flex-col">
   <label for="role" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Security Privilege Level</label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors shadow-inner">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
     </svg>
    </span>
    <select id="role" name="role" required class="input-premium pl-14 appearance-none">
     <option value="cashier" {{ old('role', $user->role ?? '') == 'cashier' ? 'selected' : '' }}>Standard Cashier Access</option>
     <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Full Administrative Control</option>
    </select>
   </div>
   @error('role') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
  </div>

  <!-- Branch -->
  <div class="flex flex-col">
   <label for="branch_id" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Operational Station Assignment</label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
     </svg>
    </span>
    <select id="branch_id" name="branch_id" required class="input-premium pl-14 appearance-none">
     <option value="">Select Operational Station</option>
     @foreach ($branches as $branch)
     <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
      {{ $branch->name }} ({{ $branch->branch_code }})
     </option>
     @endforeach
    </select>
   </div>
   @error('branch_id') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
  </div>

  <!-- Password -->
  <div class="flex flex-col">
   <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
    {{ isset($user) ? 'Overwrite Security Key (Leave blank to keep current)' : 'Initialize Security Key' }}
   </label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
     </svg>
    </span>
    <input type="password" id="password" name="password" {{ isset($user) ? '' : 'required' }}
     class="input-premium pl-14" placeholder="••••••••">
   </div>
   @error('password') <span class="text-rose-500 text-[10px] font-black uppercase mt-2 tracking-widest">{{ $message }}</span> @enderror
  </div>

  <!-- Confirm Password -->
  <div class="flex flex-col">
   <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Verify Security Key</label>
   <div class="relative group">
    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
     </svg>
    </span>
    <input type="password" id="password_confirmation" name="password_confirmation" {{ isset($user) ? '' : 'required' }}
     class="input-premium pl-14" placeholder="••••••••">
   </div>
  </div>
 </div>

 <div class="pt-12 flex items-center space-x-6 border-t border-slate-50">
  <button type="submit" class="btn-primary !px-12 !py-4 group">
   <span class="text-xs uppercase tracking-[0.2em] font-black">
    {{ isset($user) ? 'Authorize Updates' : 'Initialize Access' }}
   </span>
   <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
   </svg>
  </button>
  <a href="{{ route('users.index') }}" class="text-[10px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest transition-colors">Abondon Setup</a>
 </div>
</form>