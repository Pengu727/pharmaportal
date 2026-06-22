<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Owner Login - PharmaPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pharmacy: {
                            50: '#f0fdf4',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-pharmacy-50 min-h-screen flex items-center justify-center font-sans antialiased">

<div class="w-full max-w-md p-4 box-border">
    <div class="bg-white px-8 py-10 rounded-2xl shadow-xl shadow-emerald-950/5 border border-emerald-500/10 text-center">
        
        <div class="w-16 h-16 bg-pharmacy-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-5 shadow-lg shadow-emerald-600/20">
            💚
        </div>
        
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Pharmacy Owner</h1>
        <p class="text-sm text-slate-500 mb-8">Manage your pharmacy and inventory</p>

        <form action="/client-login" method="POST" class="space-y-4">
            @csrf

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email Address
                </label>
                <input type="email" name="email" placeholder="name@example.com" required
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
            </div>

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Password
                </label>
                <input type="password" name="password" placeholder="••••••••" required
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
            </div>

            <button type="submit" 
                    class="w-full mt-2 py-3.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-lg transition duration-150 active:scale-[0.99] shadow-md shadow-emerald-600/10">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-sm text-slate-500">
            Back to <a href="/client-login" class="font-semibold text-pharmacy-600 hover:underline">Patient Portal</a>
        </div>
        
    </div>
</div>

</body>
</html>
