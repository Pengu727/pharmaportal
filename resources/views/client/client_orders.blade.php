<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - PharmaPortal</title>
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
            <div class="flex justify-between h-16 items-center relative">
                <div class="flex items-center space-x-3">
                    <a href="/client-dashboard" class="cursor-pointer text-2xl flex items-center space-x-2">
                        <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain">
                        <span class="font-bold text-xl text-slate-800">PharmaPortal</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/client-dashboard" class="text-sm font-bold text-slate-500 hover:text-slate-700">Back to Catalog</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 pt-10">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">My Reservations</h1>
            <p class="text-xs text-slate-500 mt-1">Track your medication reservations. Present your 6-digit code at the pharmacy counter.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($reservations as $reservation)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2.5 mb-1.5">
                                @if($reservation->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">Pending</span>
                                @elseif($reservation->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Completed</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wide">Expired</span>
                                @endif
                            </div>

                            <div class="mt-2 mb-1">
                                <span class="text-[10px] text-slate-400 font-mono">ID: {{ $reservation->_id }}</span>
                            </div>
                            <p class="text-sm font-bold text-slate-800">{{ $reservation->product_name }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                Pharmacy: <span class="font-medium text-slate-600">{{ $reservation->pharmacy_name ?? 'N/A' }}</span>
                                • Qty: <span class="font-medium">{{ $reservation->quantity ?? 1 }}</span>
                            </p>

                            @php
                                $pharmacy = $reservation->pharmacy;
                            @endphp
                            @if($pharmacy)
                                <div class="grid grid-cols-1 gap-2 bg-slate-50 p-3 rounded-xl text-xs border border-slate-100 mt-2 mb-2 w-max">
                                    <div>
                                        <span class="text-[10px] text-slate-400 block mb-0.5 font-bold uppercase tracking-wider"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Address</span>
                                        <span class="font-bold text-slate-700 block">{{ $pharmacy->adresse_complete ?? 'N/A' }}</span>
                                        <span class="text-slate-500">{{ $pharmacy->commune ?? '' }}, {{ $pharmacy->wilaya ?? '' }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="text-[10px] text-slate-400 block mb-0.5 font-bold uppercase tracking-wider">🗺 Location</span>
                                        @if($pharmacy->google_maps_url)
                                            <a href="{{ $pharmacy->google_maps_url }}" target="_blank" class="font-bold text-pharmacy-600 hover:underline">Open in Google Maps →</a>
                                        @else
                                            <span class="text-slate-500">No map link available</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <p class="text-xs text-slate-400 mt-1">
                                Reserved: {{ $reservation->created_at ? $reservation->created_at->format('M d, Y H:i') : 'N/A' }}
                                • Expires: {{ $reservation->expires_at ? $reservation->expires_at->format('M d, Y H:i') : 'N/A' }}
                            </p>
                        </div>

                        <div class="text-left sm:text-right w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                            @if($reservation->status === 'pending')
                                <div>
                                    <span class="block text-[9px] font-bold text-pharmacy-600 uppercase tracking-wider">Your Pickup Code</span>
                                    <span class="text-xl font-black text-slate-800 tracking-widest font-mono bg-slate-50 px-3 py-1 rounded-lg border border-slate-200 block mt-1">
                                        {{ $reservation->confirmation_code }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-1">Give this code to the pharmacist</span>
                                </div>
                            @elseif($reservation->status === 'completed')
                                <span class="text-xs font-semibold text-emerald-600 italic"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block text-emerald-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Collected</span>
                            @else
                                <span class="text-xs font-semibold text-slate-400 italic">⏰ Expired</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-12 text-center">
                    <span class="text-4xl block mb-3"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></span>
                    <h3 class="text-sm font-bold text-slate-700">No Reservations Found</h3>
                    <p class="text-xs text-slate-400 mt-1">You haven't reserved any products yet.</p>
                    <a href="/client-dashboard" class="inline-block mt-4 text-xs font-bold text-pharmacy-600 hover:underline">Browse Catalog →</a>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>