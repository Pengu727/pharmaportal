<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 border border-slate-100">
        <div class="text-center mb-8">
            <span class="text-4xl block mb-2">🛡️</span>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">System Core Admin</h1>
            <p class="text-xs text-slate-400 mt-1">Isolated administrative database terminal access portal</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Email Account</label>
                <input type="email" name="email" value="admin@example.com" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Secure Password Key</label>
                <input type="password" name="password" value="password123" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:border-emerald-600 focus:bg-white transition">
            </div>
            <button type="submit" class="w-full py-3.5 bg-slate-800 hover:bg-emerald-600 text-white font-bold rounded-xl transition text-sm shadow-md">
                Authenticate Security Clearance
            </button>
        </form>
    </div>
</body>
</html>