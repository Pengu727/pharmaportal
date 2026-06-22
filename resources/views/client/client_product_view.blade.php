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
                    <span>💚</span> <span>PharmaPortal</span>
                </a>
                <a href="/client-dashboard" class="text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-pharmacy-100 text-pharmacy-700 rounded-full text-xs font-bold uppercase tracking-wide">
                            {{ $product->category ?? 'General' }}
                        </span>
                        <span class="text-xs bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full font-medium">
                            Verified Medicine
                        </span>
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">{{ $product->name }}</h1>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $product->description ?? 'No detailed description structural maps available for this item.' }}</p>

                    <div class="border-t border-slate-100 pt-4 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Formulation Class</span>
                            <span class="text-sm font-bold text-slate-700">{{ $product->metadata['form'] ?? 'Tablet / Standard' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Dosage Reference</span>
                            <span class="text-sm font-bold text-slate-700">{{ $product->metadata['dosage'] ?? 'As prescribed' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span>🏢</span> Dispensing Pharmacy Info
                    </h3>
                    
                    @if($pharmacy)
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-pharmacy-700">{{ $pharmacy->name }}</h4>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">ID: {{ $pharmacy->pharmacy_id }}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl text-sm border border-slate-100">
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5">📍 Localisation Region</span>
                                    <span class="font-bold text-slate-700 block">{{ $pharmacy->wilaya ?? 'Algiers' }}</span>
                                    <span class="text-xs text-slate-500">{{ $pharmacy->commune ?? 'USTHB Area' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block mb-0.5">📞 Contact Details</span>
                                    <span class="font-bold text-slate-700 block">{{ $pharmacy->phone ?? 'N/A' }}</span>
                                    <span class="text-xs text-slate-500">Call for prescription inquiries</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-700 flex gap-2">
                            <span>⚠️</span>
                            <div>
                                <p class="font-bold">Pharmacy Profile Offline</p>
                                <p class="text-xs mt-0.5">The medicine is listed, but the dispensing location profile details are currently missing.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm sticky top-24 space-y-6">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block tracking-wider uppercase">Unit Price</span>
                        <div class="text-3xl font-black text-pharmacy-600 mt-1">{{ $product->price }} <span class="text-lg font-bold">DA</span></div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500 font-medium">Stock Status</span>
                            <span class="px-2 py-0.5 font-bold rounded-full {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->stock > 0 ? 'Available' : 'Out of stock' }}
                            </span>
                        </div>
                        <div class="text-sm font-bold text-slate-700 mt-2 flex justify-between items-center">
                            <span>In-Store Units:</span>
                            <span>{{ $product->stock }} packs</span>
                        </div>
                    </div>

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id ?? $product->_id }}">
                        
                        <button type="submit" {{ $product->stock > 0 ? '' : 'disabled' }} 
                                class="w-full py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition text-center shadow-md shadow-emerald-600/10 flex items-center justify-center gap-2 {{ $product->stock > 0 ? '' : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none' }}">
                            <span>🔒</span> {{ $product->stock > 0 ? 'Reserve Securely Now' : 'Stock Unavailable' }}
                        </button>
                    </form>
                    
                    <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                        Reservations hold stock items instantly. Bring your valid physical medical prescription to the pharmacy counter when collecting.
                    </p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>