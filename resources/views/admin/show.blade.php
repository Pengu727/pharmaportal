<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Inspector - {{ $pharmacy->nom_pharmacie ?? 'Pharmacy' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">💚</span>
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
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active Owner Profile Document</span>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight mt-1">{{ $pharmacy->nom_pharmacie ?? 'Unnamed Pharmacy' }}</h1>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-700 transition">← Back Dashboard</a>
                    <a href="{{ route('admin.edit', ['id' => (string)$pharmacy->_id]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">Update Record</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Account & Owner Info</h3>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Full Legal Identity</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $pharmacy->nom }} {{ $pharmacy->prenom }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Direct Contact Channel</span>
                        <span class="text-sm font-mono text-slate-800">{{ $pharmacy->email }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Telephone Registry (num_tel)</span>
                        <span class="text-sm text-slate-800">{{ $pharmacy->num_tel }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Operational Shift Frame</span>
                        <span class="text-sm text-slate-800 font-medium">Runs Daily: {{ $pharmacy->heure_ouverture ?? '07:30' }} to {{ $pharmacy->heure_fermeture ?? '21:00' }}</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b pb-1">Geographical Parameters</h3>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Commercial Identification (RC)</span>
                        <span class="text-sm font-mono text-slate-800 font-bold">{{ $pharmacy->registre_commerce ?? 'No RC data' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Structured Address Specification</span>
                        <div class="text-sm text-slate-600 leading-relaxed">
                            @if(is_array($pharmacy->adresse_complete))
                                Bâtiment {{ $pharmacy->adresse_complete['numero_immeuble'] ?? '33' }}, {{ $pharmacy->adresse_complete['nom_rue'] ?? 'Rue Didouche Mourad' }}<br>
                                {{ $pharmacy->adresse_complete['quartier'] ?? 'Place Maurice Audin' }}, {{ $pharmacy->adresse_complete['commune'] ?? 'Alger Centre' }}<br>
                                {{ $pharmacy->adresse_complete['wilaya'] ?? 'Alger' }} — {{ $pharmacy->adresse_complete['code_postal'] ?? '16000' }}
                            @else
                                {{ $pharmacy->adresse_complete ?? 'No address string specified' }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">📍 Google Maps Location Workspace</h4>
                
                @if(isset($pharmacy->location['coordinates']) && is_array($pharmacy->location['coordinates']))
                    <div class="w-full flex flex-col gap-3">
                        
                        <div class="w-full h-80 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-10">
                            <iframe 
                                class="w-full h-full border-0"
                                loading="lazy" 
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://maps.google.com/maps?q={{ $pharmacy->location['coordinates'][1] }},{{ $pharmacy->location['coordinates'][0] }}&hl=en&z=16&output=embed">
                            </iframe>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            <span class="text-slate-500 font-medium">
                                Spatial Registry Keys: 
                                <strong class="text-slate-800 font-mono">Lat {{ $pharmacy->location['coordinates'][1] }}</strong>, 
                                <strong class="text-slate-800 font-mono">Lng {{ $pharmacy->location['coordinates'][0] }}</strong>
                            </span>
                            @if(isset($pharmacy->google_maps_link) && $pharmacy->google_maps_link)
                                <a href="{{ $pharmacy->google_maps_link }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-center shadow-md">
                                    🗺️ View Large / Open Full App
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="w-full p-4 bg-slate-100 rounded-2xl border border-slate-200 text-slate-500 text-sm">
                        No coordinate array properties found on this record.
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>