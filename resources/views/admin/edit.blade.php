<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $pharmacy->nom_pharmacie ?? 'Pharmacy' }} - PharmaPortal Admin</title>
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
            <h2 class="text-xl font-black text-slate-800 mb-6 tracking-tight border-b pb-3">Edit Pharmacy & Owner</h2>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.update', ['id' => (string)$pharmacy->_id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Owner Section --}}
                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 space-y-4">
                    <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wide"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Owner Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner Last Name</label>
                            <input type="text" name="owner_nom" value="{{ $owner->nom ?? '' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner First Name</label>
                            <input type="text" name="owner_prenom" value="{{ $owner->prenom ?? '' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Owner Email</label>
                        <input type="email" name="owner_email" value="{{ $owner->email ?? '' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:bg-white transition">
                    </div>
                </div>

                {{-- Pharmacy Section --}}
                <div class="p-5 bg-slate-50/70 rounded-2xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-bold text-emerald-700 uppercase tracking-wide">🏪 Pharmacy Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pharmacy Name</label>
                            <input type="text" name="nom_pharmacie" value="{{ $pharmacy->nom_pharmacie ?? '' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Commercial Registration (RC)</label>
                            <input type="text" name="registre_commerce" value="{{ $pharmacy->registre_commerce ?? '' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="text" name="num_tel" value="{{ $pharmacy->num_tel }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Wilaya</label>
                            <input type="text" name="wilaya" value="{{ $pharmacy->wilaya }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Commune</label>
                            <input type="text" name="commune" value="{{ $pharmacy->commune }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Opening Hour</label>
                            <input type="time" name="heure_ouverture" value="{{ $pharmacy->heure_ouverture ?? '07:30' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Closing Hour</label>
                            <input type="time" name="heure_fermeture" value="{{ $pharmacy->heure_fermeture ?? '21:00' }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Complete Address</label>
                        <input type="text" name="adresse_complete" value="{{ is_array($pharmacy->adresse_complete) ? '' : ($pharmacy->adresse_complete ?? '') }}" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition" placeholder="e.g., 33 Rue Didouche Mourad, Alger">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Google Maps Link</label>
                        <input type="url" id="google_maps_link" name="google_maps_link" value="{{ $pharmacy->google_maps_link ?? '' }}" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none transition font-mono text-xs" placeholder="Paste link here to update coordinates...">
                    </div>

                    <div id="coordinate_feedback" class="bg-white/50 p-3 rounded-xl border border-slate-200/60 text-xs text-slate-500 transition">
                        Current Values: Lat {{ $pharmacy->location['coordinates'][1] ?? 'None' }}, Lng {{ $pharmacy->location['coordinates'][0] ?? 'None' }}
                    </div>
                </div>

                <input type="hidden" id="latitude" name="latitude" value="{{ $pharmacy->location['coordinates'][1] ?? '' }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ $pharmacy->location['coordinates'][0] ?? '' }}">

                <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold rounded-xl text-xs transition">
                        ← Cancel & Back
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-md">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </main>

   <script>
    document.getElementById('google_maps_link').addEventListener('input', function(e) {
        const url = e.target.value.trim();
        const feedback = document.getElementById('coordinate_feedback');

        const regex = /@([0-9.-]+),([0-9.-]+)/;
        const regexFallback = /([0-9.-]+)\s*,\s*([0-9.-]+)/;
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

        if (url !== "") {
            feedback.className = "bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs text-amber-700 transition";
            feedback.innerHTML = "<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-amber-500"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> Link detected but coordinates couldn't be parsed from this format.";
        }
    });
</script>

</body>
</html>