<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Login - POS Kasir</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-900">
 <div class="min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full">
   <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100">
    <div class="text-center mb-10">
     <h1 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h1>
     <p class="text-slate-500">Sign in to your POS account</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-6">
     @csrf
     <div>
      <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
      <input type="email" name="email" id="email" required
       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
       placeholder="name@company.com" value="{{ old('email') }}">
      @error('email')
      <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
      @enderror
     </div>

     <div>
      <div class="flex items-center justify-between mb-2">
       <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
       <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Forgot password?</a>
      </div>
      <input type="password" name="password" id="password" required
       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
       placeholder="••••••••">
     </div>

     <div class="flex items-center">
      <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
      <label for="remember" class="ml-2 block text-sm text-slate-600">Remember me</label>
     </div>

     <button type="submit"
      class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-all active:scale-[0.98] shadow-lg shadow-slate-900/10">
      Sign In
     </button>
    </form>

    <div class="mt-8 pt-8 border-t border-slate-100 text-center">
     <p class="text-sm text-slate-500 italic">"Simplifying your business transactions."</p>
    </div>
   </div>
  </div>
 </div>
</body>

</html>