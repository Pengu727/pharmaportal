<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - PharmaPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pharmacy: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased pb-12">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/client-dashboard" class="flex items-center space-x-2 text-xl font-bold text-slate-800">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain"> <span>PharmaPortal</span>
                </a>
                <a href="/client-dashboard" class="text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-medium rounded-xl">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="md:col-span-2 space-y-6">
                {{-- Product Information --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="px-2.5 py-1 bg-pharmacy-100 text-pharmacy-700 rounded-full text-xs font-bold uppercase tracking-wide">
                            {{ $product->category ?? 'General' }}
                        </span>
                        <!-- Status Badge removed to hide internal expiration logic from clients -->
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">{{ $product->name }}</h1>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $product->description ?? 'No detailed description available.' }}</p>

                    <div class="border-t border-slate-100 pt-4 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Category</span>
                            <span class="text-sm font-bold text-slate-700">{{ $product->category ?? 'General' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Dosage</span>
                            <span class="text-sm font-bold text-slate-700">{{ $product->dosage ?? ($product->metadata['dosage'] ?? 'As prescribed') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Laboratory</span>
                            <span class="text-sm font-bold text-slate-700">{{ $product->laboratory ?? ($product->metadata['laboratory'] ?? 'N/A') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Price</span>
                            <span class="text-sm font-bold text-pharmacy-600">{{ number_format($product->price, 2) }} DA</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Availability</span>
                            <span class="text-sm font-bold {{ $product->stock > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? 'Available' : 'Out of Stock' }}
                            </span>
                        </div>
                        <!-- Expiration Date hidden from clients -->
                    </div>
                </div>

                {{-- Pharmacy Information --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span>🏢</span> Dispensing Pharmacy
                    </h3>

                    @if($pharmacy)
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-pharmacy-700">{{ $pharmacy->nom_pharmacie }}</h4>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl text-sm border border-slate-100">
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Address</span>
                                    <span class="font-bold text-slate-700 block">{{ $pharmacy->adresse_complete ?? 'N/A' }}</span>
                                    <span class="text-xs text-slate-500">{{ $pharmacy->commune ?? '' }}, {{ $pharmacy->wilaya ?? '' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Phone</span>
                                    <span class="font-bold text-slate-700 block">{{ $pharmacy->num_tel ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5">🕐 Opening Hours</span>
                                    <span class="font-bold text-slate-700 block">{{ $pharmacy->heure_ouverture ?? '07:30' }} — {{ $pharmacy->heure_fermeture ?? '21:00' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5">🗺 Location</span>
                                    @if($pharmacy->google_maps_url)
                                        <a href="{{ $pharmacy->google_maps_url }}" target="_blank" class="font-bold text-pharmacy-600 hover:underline">Open in Google Maps →</a>
                                    @else
                                        <span class="text-slate-500">No map link available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700 flex gap-2">
                            <span><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-amber-500"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></span>
                            <div>
                                <p class="font-bold">Pharmacy Profile Not Found</p>
                                <p class="text-xs mt-0.5">The dispensing location details are currently unavailable.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar: Price & Reserve --}}
            <div class="md:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm sticky top-24 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block tracking-wider uppercase">Unit Price</span>
                        <div class="text-3xl font-black text-pharmacy-600 mt-1">{{ $product->price }} <span class="text-lg font-bold">DA</span></div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500 font-medium">Stock Status</span>
                            <span class="px-2 py-0.5 font-bold rounded-full {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock > 0 ? 'Available' : 'Out of stock' }}
                            </span>
                        </div>
                        <!-- Exact quantity hidden for clients -->
                        <!-- Internal status and expiration hidden from clients -->
                    </div>

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->_id }}">
                        <input type="hidden" name="pharmacy_id" value="{{ $product->pharmacy_id }}">

                        @if($product->stock > 1)
                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Quantity</label>
                                <select name="quantity" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:outline-none focus:border-pharmacy-600 transition">
                                    @for($i = 1; $i <= min($product->stock, 5); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="quantity" value="1">
                        @endif

                        <button type="submit" {{ $product->stock > 0 ? '' : 'disabled' }}
                                class="w-full py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition text-center shadow-md shadow-emerald-600/10 flex items-center justify-center gap-2 {{ $product->stock > 0 ? '' : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none' }}">
                            <span>🔒</span> {{ $product->stock > 0 ? 'Reserve Product' : 'Out of Stock' }}
                        </button>
                    </form>

                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                        Reservation valid for 24 hours. Bring your prescription to collect at the pharmacy counter.
                    </p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>