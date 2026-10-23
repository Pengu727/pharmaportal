<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacies Near Me - PharmaPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { pharmacy: { 50: '#f0fdf4', 100: '#dcfce7', 600: '#059669', 700: '#047857' } } } } }
    </script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased pb-12">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/client-dashboard" class="flex items-center space-x-2 text-xl font-bold text-slate-800">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain"> <span>PharmaPortal</span>
                </a>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/client-dashboard" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600">Dashboard</a>
                    <a href="/client-nearme" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Near Me</a>
                    <a href="{{ route('client.orders') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600">My Orders</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-slate-600 hover:text-pharmacy-600 focus:outline-none p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="/client-dashboard" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">Dashboard</a>
                <a href="/client-nearme" class="block px-3 py-2 rounded-lg text-base font-bold text-pharmacy-600 bg-pharmacy-50 hover:bg-pharmacy-100 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Near Me</a>
                <a href="{{ route('client.orders') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">My Orders</a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight mb-2"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Pharmacies Near You</h1>
            <p class="text-sm text-slate-500 mb-4">Find pharmacies closest to your location with available medications.</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <form action="{{ route('client.nearme') }}" method="GET" class="flex-1 flex flex-col sm:flex-row gap-2" id="nearmeForm">
                    <input type="hidden" name="lat" id="user_lat" value="{{ $lat ?? '' }}">
                    <input type="hidden" name="lng" id="user_lng" value="{{ $lng ?? '' }}">
                    <div class="flex-1 relative w-full">
                        <input type="text" name="search" id="search-input" autocomplete="off" value="{{ $search ?? '' }}" placeholder="Filter by medication name (optional)..."
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-pharmacy-600 transition">
                        <div id="suggestions-box" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg hidden z-50 overflow-hidden">
                        </div>
                    </div>
                    <button type="button" onclick="getLocation()" class="w-full sm:w-auto px-5 py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Detect My Location
                    </button>
                </form>
            </div>

            <div id="locationStatus" class="mt-3 text-xs text-slate-500 hidden"></div>
            
            <div id="manualCoords" class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl hidden">
                <p class="text-sm font-semibold text-slate-700 mb-2">Enter Coordinates Manually:</p>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" id="manual_lat" placeholder="Latitude (e.g. 36.75)" class="w-full sm:flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <input type="text" id="manual_lng" placeholder="Longitude (e.g. 3.04)" class="w-full sm:flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <button type="button" onclick="submitManual()" class="w-full sm:w-auto px-4 py-2 bg-slate-800 text-white font-bold rounded-lg text-sm">Search</button>
                </div>
            </div>
        </div>

        @if($lat && $lng)
            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Your coordinates: {{ $lat }}, {{ $lng }}
                @if(!empty($search))
                    — Searching for: <strong>"{{ $search }}"</strong>
                @endif
            </div>

            @if($pharmacies->count() > 0)
                <div class="space-y-4">
                    @foreach($pharmacies as $pharmacy)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h2 class="text-lg font-bold text-slate-800">{{ $pharmacy->nom_pharmacie }}</h2>
                                        <span class="px-2.5 py-0.5 bg-pharmacy-50 text-pharmacy-700 rounded-full text-xs font-bold border border-pharmacy-200">
                                            {{ $pharmacy->distance }} km
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> {{ $pharmacy->adresse_complete ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $pharmacy->commune ?? '' }}, {{ $pharmacy->wilaya ?? '' }} •
                                        🕐 {{ $pharmacy->heure_ouverture ?? '07:30' }} — {{ $pharmacy->heure_fermeture ?? '21:00' }} •
                                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> {{ $pharmacy->num_tel ?? 'N/A' }}
                                    </p>

                                    @if($pharmacy->google_maps_url)
                                        <a href="{{ $pharmacy->google_maps_url }}" target="_blank" class="inline-block mt-2 text-xs font-bold text-pharmacy-600 hover:underline">
                                            🗺 Open in Google Maps →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($pharmacy->matching_products && $pharmacy->matching_products->count() > 0)
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Available Products ({{ $pharmacy->matching_products->count() }})</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($pharmacy->matching_products->take(6) as $product)
                                            <a href="{{ route('client.product_view', ['id' => $product->_id]) }}"
                                               class="p-3 bg-slate-50 rounded-xl border border-slate-100 hover:bg-white hover:border-slate-200 transition flex justify-between items-center">
                                                <div>
                                                    <span class="text-sm font-bold text-slate-700 block">{{ $product->name }}</span>
                                                    <span class="text-xs font-bold {{ $product->stock > 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ $product->stock > 0 ? 'Available' : 'Out of Stock' }}</span>
                                                </div>
                                                <span class="text-sm font-bold text-pharmacy-600">{{ $product->price }} DA</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-12 text-center">
                    <span class="text-4xl block mb-3"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M3 21h18"></path><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path><path d="M9 10h6"></path><path d="M12 7v6"></path></svg></span>
                    <h3 class="text-sm font-bold text-slate-700">No Pharmacies Found Nearby</h3>
                    <p class="text-xs text-slate-400 mt-1">Try broadening your search or removing filters.</p>
                </div>
            @endif
        @else
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-12 text-center">
                <span class="text-5xl block mb-4"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
                <h3 class="text-lg font-bold text-slate-700">Enable Location Access</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Click "Detect My Location" to find pharmacies sorted by distance from you.</p>
            </div>
        @endif
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('suggestions-box');

    if (!searchInput || !suggestionsBox) return;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.add('hidden');
            return;
        }

        fetch(`/products/suggest?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';

                if (data.length === 0) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }

                data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 cursor-pointer transition flex items-center justify-between border-b border-slate-50 last:border-0';
                    
                    const textSpan = document.createElement('span');
                    textSpan.className = 'font-medium truncate pr-4';
                    textSpan.textContent = item.text;
                    
                    const typeSpan = document.createElement('span');
                    typeSpan.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider shrink-0 ';
                    
                    if (item.type === 'Product') {
                        typeSpan.className += 'bg-blue-50 text-blue-600 border border-blue-200';
                    } else if (item.type === 'Pharmacy') {
                        typeSpan.className += 'bg-emerald-50 text-emerald-600 border border-emerald-200';
                    } else if (item.type === 'Category') {
                        typeSpan.className += 'bg-amber-50 text-amber-600 border border-amber-200';
                    } else if (item.type === 'Laboratory') {
                        typeSpan.className += 'bg-purple-50 text-purple-600 border border-purple-200';
                    } else {
                        typeSpan.className += 'bg-slate-100 text-slate-600 border border-slate-200';
                    }
                    typeSpan.textContent = item.type;
                    
                    div.appendChild(textSpan);
                    div.appendChild(typeSpan);

                    div.addEventListener('click', function() {
                        searchInput.value = item.text;
                        suggestionsBox.classList.add('hidden');
                        document.getElementById('nearmeForm').submit();
                    });

                    suggestionsBox.appendChild(div);
                });

                suggestionsBox.classList.remove('hidden');
            });
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add('hidden');
        }
    });
});

        function getLocation() {
            const status = document.getElementById('locationStatus');
            status.classList.remove('hidden');
            status.innerHTML = '⏳ Requesting location permission...';
            status.className = 'mt-3 text-xs text-amber-600 font-medium';

            if (!navigator.geolocation) {
                status.innerHTML = '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-red-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Geolocation is disabled by your browser on insecure connections (HTTP). Please enter manually below or use localhost/HTTPS.';
                status.className = 'mt-3 text-xs text-red-600 font-medium';
                document.getElementById('manualCoords').classList.remove('hidden');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('user_lat').value = position.coords.latitude.toFixed(6);
                    document.getElementById('user_lng').value = position.coords.longitude.toFixed(6);

                    status.innerHTML = '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-emerald-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Location detected! Searching nearby pharmacies...';
                    status.className = 'mt-3 text-xs text-emerald-600 font-medium';

                    document.getElementById('nearmeForm').submit();
                },
                function(error) {
                    let msg = '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-red-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Location access denied.';
                    if (error.code === error.TIMEOUT) msg = '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-red-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Location request timed out.';
                    if (error.code === error.POSITION_UNAVAILABLE) msg = '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-red-500"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Location unavailable.';

                    status.innerHTML = msg + ' Please enter coordinates manually below.';
                    status.className = 'mt-3 text-xs text-red-600 font-medium';
                    document.getElementById('manualCoords').classList.remove('hidden');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
            );
        }

        // Auto-trigger removed to ensure mobile compatibility.
        // Mobile browsers require a direct user gesture (button tap) to display the native permission dialog.
        // Calling this on page load silently blocks the prompt.

        function submitManual() {
            const lat = document.getElementById('manual_lat').value;
            const lng = document.getElementById('manual_lng').value;
            if (!lat || !lng) { alert('Please enter both latitude and longitude.'); return; }
            document.getElementById('user_lat').value = lat;
            document.getElementById('user_lng').value = lng;
            document.getElementById('nearmeForm').submit();
        }
    </script>

</body>
</html>
