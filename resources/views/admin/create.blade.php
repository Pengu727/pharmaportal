<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registry Console - New Pharmacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">💚</span>
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
            <h2 class="text-xl font-black text-slate-800 mb-6 tracking-tight border-b pb-3">Deploy New Owner License Entry</h2>

            <form action="{{ route('admin.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nom (Owner Surname)</label>
                        <input type="text" name="nom" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-emerald-600 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Prénom (First Name)</label>
                        <input type="text" name="prenom" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-emerald-600 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Connection Coordinate</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-emerald-600 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Telephone Line Target (num_tel)</label>
                        <input type="tel" name="num_tel" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white focus:border-emerald-600 transition">
                    </div>
                </div>

                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-bold text-emerald-700 tracking-wide uppercase">Core Pharmacy Commercial Record Profile</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nom Pharmacie (Store Label Name)</label>
                            <input type="text" name="nom_pharmacie" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Registre du Commerce Token (RC)</label>
                            <input type="text" name="registre_commerce" placeholder="e.g., 16/00-0987654B26" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
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
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">📍 Google Maps Link</label>
                        <input type="url" name="google_maps_link" placeholder="https://www.google.com/maps/..." required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition" id="maps_link">
                        <p class="text-xs text-slate-500 mt-1">📌 Paste your Google Maps link - coordinates will be extracted automatically</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Complete Address</label>
                        <input type="text" name="adresse_complete" placeholder="e.g., 45, Rue Didouche Mourad, Alger Centre, Alger 16000" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                    </div>

                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div id="coordinate_feedback" class="bg-white/50 p-3 rounded-xl border border-amber-200/50 text-xs text-slate-600 transition">
                        ⏳ Awaiting Google Maps URL to extract coordinates...
                    </div>
                </div>

                <input type="hidden" name="wilaya" value="Alger">
                <input type="hidden" name="commune" value="Alger Centre">
                <input type="hidden" name="date_naissance" value="1972-11-05">

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold rounded-xl text-xs transition">
                        ← Back List
                    </a>
                    <div class="flex items-center space-x-2">
                        <button type="reset" id="form_reset" class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-xs transition">
                            Clear Fields
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md">
                            Save Pharmacy
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </main>

    <script>
    document.getElementById('google_maps_link').addEventListener('input', function(e) {
        const url = e.target.value.trim();
        const feedback = document.getElementById('coordinate_feedback');
        
        // Match ANY pair of decimal numbers separated by a comma anywhere in the path string
        const regexCoordinates = /([0-9.-]+)\s*,\s*([0-9.-]+)/;
        let match = url.match(regexCoordinates);
        
        if (match && match[1] && match[2]) {
            const lat = parseFloat(match[1]);
            const lng = parseFloat(match[2]);
            
            // Validate that coordinates represent physical earthly points
            if (Math.abs(lat) <= 90 && Math.abs(lng) <= 180 && lat !== 0 && lng !== 0) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                feedback.className = "bg-emerald-50 p-3 rounded-xl border border-emerald-200 text-xs text-emerald-700 font-medium transition";
                feedback.innerHTML = `✅ Successfully extracted coordinates: Lat ${lat}, Lng ${lng}`;
                return;
            }
        }
        
        if (url !== "") {
            feedback.className = "bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs text-amber-700 transition";
            feedback.innerHTML = "⚠️ Custom link format discovered, but numerical coordinate configurations couldn't be parsed out.";
        }
    });
</script>
</body>
</html>