<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal - Create Account</title>
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
<body class="bg-pharmacy-50 min-h-screen flex items-center justify-center font-sans antialiased py-10">

<div class="w-full max-w-lg p-4 box-border">
    <div class="bg-white px-8 py-10 rounded-2xl shadow-xl shadow-emerald-950/5 border border-emerald-500/10 text-center">
        
        <a href="/client-dashboard" class="w-16 h-16 bg-pharmacy-600 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-5 shadow-lg shadow-emerald-600/20 block select-none">
            💚
        </a>
        
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Create Account</h1>
        <p class="text-sm text-slate-400 mb-6">Register a new client profile below</p>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl text-left">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/client-register" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nom</label>
                    <input type="text" name="nom" placeholder="Doe" required value="{{ old('nom') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Prénom</label>
                    <input type="text" name="prenom" placeholder="John" required value="{{ old('prenom') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
            </div>

            <div class="text-left">
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" placeholder="name@example.com" required value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <input type="text" name="num_tel" placeholder="0555123456" required value="{{ old('num_tel') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Date of Birth</label>
                    <input type="date" name="date_naissance" required value="{{ old('date_naissance') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Wilaya</label>
                    <input type="text" name="wilaya" placeholder="Algiers" required value="{{ old('wilaya') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Commune</label>
                    <input type="text" name="commune" placeholder="El Biar" required value="{{ old('commune') }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>

                <div class="text-left">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                           class="w-full px-4 py-3 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
            </div>

            <input type="hidden" name="role" value="client">

            <div class="flex items-start text-sm text-left pt-1">
                <input type="checkbox" id="terms" required class="w-4 h-4 mt-0.5 mr-2 rounded border-slate-300 text-pharmacy-600 focus:ring-pharmacy-600 cursor-pointer">
                <label for="terms" class="text-slate-500 select-none cursor-pointer">
                    I agree to the <a href="#" class="font-semibold text-pharmacy-600 hover:underline">Terms of Service</a> and <a href="#" class="font-semibold text-pharmacy-600 hover:underline">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" 
                    class="w-full mt-2 py-3.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-lg transition duration-150 active:scale-[0.99] shadow-md shadow-emerald-600/10">
                Register Account
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 text-sm text-slate-500">
            Already have an account? <a href="/client-login" class="font-bold text-pharmacy-600 hover:underline">Sign In Instead</a>
        </div>
    </div>
</div>

</body>
</html>