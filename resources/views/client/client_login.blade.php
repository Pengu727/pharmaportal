<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal - Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pharmacy: { 50: '#f0fdf4', 600: '#059669', 700: '#047857' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-pharmacy-50 min-h-screen flex items-center justify-center font-sans antialiased">

<div class="w-full max-w-sm p-4">
    <div class="bg-white px-8 py-10 rounded-2xl shadow-xl shadow-emerald-950/5 border border-emerald-500/10 text-center">
        
        <a href="/client-dashboard" class="w-16 h-16 bg-pharmacy-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-5 shadow-lg shadow-emerald-600/20 block select-none">
            💚
        </a>
        
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Welcome Back</h1>
        <p class="text-sm text-slate-400 mb-6">Access your prescription hold system</p>

        @if(session('status'))
            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl text-left">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl text-left">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/client-login" method="POST" class="space-y-4">
            @csrf 

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com" value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:ring-4 focus:ring-pharmacy-600/10 transition">
            </div>

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:ring-4 focus:ring-pharmacy-600/10 transition">
            </div>

            <button type="submit" 
                    class="w-full mt-4 py-3.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-lg transition active:scale-[0.99] shadow-md shadow-emerald-600/10">
                Log in
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-sm text-slate-500">
            Don't have an account? <a href="/client-register" class="font-bold text-pharmacy-600 hover:underline">Create Account</a>
        </div>
    </div>
</div>

</body>
</html>