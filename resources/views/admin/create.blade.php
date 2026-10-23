<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Pharmacy - PharmaPortal Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-slate-800">PharmaPortal</span>
                </div>
                <div class="w-9 h-9 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(session('admin_name', 'A'), 0, 1) }}
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 mt-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h2 class="text-xl font-black text-slate-800 mb-6 tracking-tight border-b pb-3">Register New Pharmacy & Owner</h2>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Owner Account Section --}}
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 space-y-4">
                    <h3 class="text-xs font-bold text-blue-700 tracking-wide uppercase"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Owner Account Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner Last Name (Nom)</label>
                            <input type="text" name="owner_nom" required value="{{ old('owner_nom') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner First Name (Prénom)</label>
                            <input type="text" name="owner_prenom" required value="{{ old('owner_prenom') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner Email (Login)</label>
                            <input type="email" name="owner_email" required value="{{ old('owner_email') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner Password</label>
                            <input type="password" name="owner_password" required minlength="6" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-600 transition">
                        </div>
                    </div>
                </div>

                {{-- Pharmacy Information Section --}}
                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-bold text-emerald-700 tracking-wide uppercase">🏪 Pharmacy Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pharmacy Name</label>
                            <input type="text" name="nom_pharmacie" required value="{{ old('nom_pharmacie') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Commercial Registration (RC)</label>
                            <input type="text" name="registre_commerce" placeholder="e.g., 16/00-0987654B26" required value="{{ old('registre_commerce') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="tel" name="num_tel" required value="{{ old('num_tel') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Wilaya</label>
                            <input type="text" name="wilaya" required value="{{ old('wilaya', 'Alger') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Commune</label>
                            <input type="text" name="commune" required value="{{ old('commune', 'Alger Centre') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Opening Hour</label>
                            <input type="time" name="heure_ouverture" value="07:30" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Closing Hour</label>
                            <input type="time" name="heure_fermeture" value="21:00" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Google Maps Link</label>
                        <input type="url" name="google_maps_link" placeholder="https://www.google.com/maps/..." class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition" id="maps_link">
                        <p class="text-xs text-slate-500 mt-1">📌 Paste your Google Maps link - coordinates will be extracted automatically</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Complete Address</label>
                        <input type="text" name="adresse_complete" placeholder="e.g., 45, Rue Didouche Mourad, Alger Centre, Alger 16000" required value="{{ old('adresse_complete') }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div id="coordinate_feedback" class="bg-white/50 p-3 rounded-xl border border-amber-200/50 text-xs text-slate-600 transition">
                        ⏳ Awaiting Google Maps URL to extract coordinates...
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold rounded-xl text-xs transition">
                        ← Back to List
                    </a>
                    <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 sm:gap-4 sm:space-x-0">
                        <button type="reset" id="form_reset" class="w-full sm:w-auto px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs transition">
                            Clear Fields
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md">
                            Save Pharmacy & Owner
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </main>

    <script>
        document.getElementById('maps_link').addEventListener('input', function(e) {
            const url = e.target.value;
            const feedback = document.getElementById('coordinate_feedback');

            const regex = /@([0-9.-]+),([0-9.-]+)/;
            const regexFallback = /([0-9.-]+),([0-9.-]+)/;
            let match = url.match(regex) || url.match(regexFallback);

            if (match && match[1] && match[2]) {
                const lat = parseFloat(match[1]);
                const lng = parseFloat(match[2]);

                if (Math.abs(lat) <= 90 && Math.abs(lng) <= 180 && lat !== 0 && lng !== 0) {
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    feedback.className = "bg-emerald-50 p-3 rounded-xl border border-emerald-200 text-xs text-emerald-700 font-medium transition";
                    feedback.innerHTML = `<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-emerald-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Coordinates extracted: Lat ${lat}, Lng ${lng}`;
                    return;
                }
            }

            if (url.trim() !== "") {
                feedback.className = "bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs text-amber-700 transition";
                feedback.innerHTML = "<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-amber-500"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> Link detected but coordinates couldn't be parsed. Server-side fallback will attempt extraction.";
            }
        });

        document.getElementById('form_reset').addEventListener('click', function() {
            const feedback = document.getElementById('coordinate_feedback');
            feedback.className = "bg-white/50 p-3 rounded-xl border border-amber-200/50 text-xs text-slate-600 transition";
            feedback.innerHTML = "⏳ Awaiting Google Maps URL to extract coordinates...";
        });
    </script>

</body>
</html>