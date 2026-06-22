<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - PharmaPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pharmacy: { 50: '#f0fdf4', 100: '#dcfce7', 600: '#059669', 700: '#047857' } } } }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased pb-12">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/client-dashboard" class="flex items-center space-x-2 text-xl font-bold text-slate-800">
                    <span>💚</span> <span>PharmaPortal</span>
                </a>
                <a href="/client-dashboard" class="text-sm font-bold text-slate-500 hover:text-slate-700">Back Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8">
            <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-slate-100">
                <div class="w-14 h-14 bg-pharmacy-100 text-pharmacy-600 rounded-2xl flex items-center justify-center text-xl font-bold uppercase">
                    {{ substr(Auth::user()->prenom ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Patient Information</h2>
                    <p class="text-xs text-slate-400">Manage your authenticated personal profile record parameters</p>
                </div>
            </div>

            <form class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nom</label>
                        <input type="text" value="{{ Auth::user()->nom }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Prénom</label>
                        <input type="text" value="{{ Auth::user()->prenom }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Numéro Téléphone (num_tel)</label>
                    <input type="tel" value="{{ Auth::user()->num_tel }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Wilaya</label>
                        <input type="text" value="{{ Auth::user()->wilaya }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Commune</label>
                        <input type="text" value="{{ Auth::user()->commune }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date de Naissance</label>
                    <input type="date" value="{{ \Carbon\Carbon::parse(Auth::user()->date_naissance)->format('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none">
                </div>

                <div class="pt-4">
                    <button type="button" class="w-full py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl transition shadow-md shadow-emerald-600/10">
                        Update Profiles Data
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>