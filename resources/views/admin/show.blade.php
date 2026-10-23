<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pharmacy->nom_pharmacie ?? 'Pharmacy' }} - PharmaPortal Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal</span>
                </div>
                <div class="w-9 h-9 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(session('admin_name', 'A'), 0, 1) }}
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 mt-8">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-8">

            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pb-6 border-b border-slate-100 mb-6">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active Pharmacy</span>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight mt-1">{{ $pharmacy->nom_pharmacie ?? 'Unnamed Pharmacy' }}</h1>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 sm:space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-700 transition">← Back Dashboard</a>
                    <a href="{{ route('admin.edit', ['id' => (string)$pharmacy->_id]) }}" class="w-full sm:w-auto text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">Edit Record</a>
                    <form action="{{ route('admin.destroy', ['id' => (string)$pharmacy->_id]) }}" method="POST" onsubmit="return confirm('Delete this pharmacy and all associated accounts?');" class="w-full sm:w-auto inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">Delete</button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Pharmacy Info</h3>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Pharmacy Name</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $pharmacy->nom_pharmacie }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Phone</span>
                        <span class="text-sm text-slate-800">{{ $pharmacy->num_tel }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Commercial Registration (RC)</span>
                        <span class="text-sm font-mono text-slate-800 font-bold">{{ $pharmacy->registre_commerce ?? 'No RC data' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Operating Hours</span>
                        <span class="text-sm text-slate-800 font-medium">{{ $pharmacy->heure_ouverture ?? '07:30' }} — {{ $pharmacy->heure_fermeture ?? '21:00' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Owner & Location</h3>
                    @if($owner)
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Owner Name</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $owner->prenom }} {{ $owner->nom }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Owner Email</span>
                            <span class="text-sm font-mono text-slate-800">{{ $owner->email }}</span>
                        </div>
                    @else
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-700">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-amber-500"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> No owner account linked to this pharmacy.
                        </div>
                    @endif
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Address</span>
                        <div class="text-sm text-slate-600 leading-relaxed">
                            {{ $pharmacy->adresse_complete ?? 'No address specified' }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Location</span>
                        <span class="text-sm text-slate-800">{{ $pharmacy->commune ?? '-' }}, {{ $pharmacy->wilaya ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Google Maps Location</h4>

                @if(isset($pharmacy->location['coordinates']) && is_array($pharmacy->location['coordinates']) && $pharmacy->location['coordinates'][0] != 0)
                    @php
                        $lat = $pharmacy->location['coordinates'][1];
                        $lng = $pharmacy->location['coordinates'][0];
                        $mapsUrl = "https://www.google.com/maps?q={$lat},{$lng}";
                    @endphp
                    <div class="w-full flex flex-col gap-3">
                        <div class="w-full h-80 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-10">
                            <iframe
                                class="w-full h-full border-0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&hl=en&z=16&output=embed">
                            </iframe>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            <span class="text-slate-500 font-medium">
                                Coordinates:
                                <strong class="text-slate-800 font-mono">Lat {{ $lat }}</strong>,
                                <strong class="text-slate-800 font-mono">Lng {{ $lng }}</strong>
                            </span>
                            <a href="{{ $mapsUrl }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-center shadow-md flex items-center gap-1.5">
                                <span>🗺</span> Open in Google Maps
                            </a>
                        </div>
                    </div>
                @else
                    <div class="w-full p-4 bg-slate-100 rounded-2xl border border-slate-200 text-slate-500 text-sm">
                        No valid coordinates stored. Add a Google Maps link when editing.
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>