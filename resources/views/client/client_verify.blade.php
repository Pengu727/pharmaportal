<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pharmacy: { 50: '#f0fdf4', 600: '#059669', 700: '#047857' } } } }
        }
    </script>
</head>
<body class="bg-pharmacy-50 min-h-screen flex items-center justify-center font-sans antialiased">
<div class="w-full max-w-sm p-4">
    <div class="bg-white px-8 py-10 rounded-2xl shadow-xl border border-emerald-500/10 text-center">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Verify Your Email</h1>
        <p class="text-sm text-slate-500 mb-6">We sent a 6-digit code to your email address.</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl text-left">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('client.verify.submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <input type="hidden" name="email" value="{{ request('email') }}">

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5 text-center">Verification Code</label>
                <input type="text" name="otp" maxlength="6" required placeholder="123456" autocomplete="off"
                       class="w-full text-center tracking-widest text-xl font-bold px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:ring-4 focus:ring-pharmacy-600/10 transition">
            </div>

            <button type="submit" class="w-full py-3.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-lg transition">
                Confirm & Create Account
            </button>
        </form>
    </div>
</div>
</body>
</html>