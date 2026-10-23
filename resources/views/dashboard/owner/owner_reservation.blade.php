<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations - PharmaPortal Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { pharmacy: { 50: '#f0fdf4', 100: '#dcfce7', 600: '#059669', 700: '#047857' } } } } }
    </script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal
                        <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Owner</span>
                    </span>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('owner.inventory') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Inventory</a>
                    <a href="{{ route('owner.reservations') }}" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1">📋 Reservations</a>
                    <a href="{{ route('owner.sellers') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">👥 Sellers</a>
                    <a href="{{ route('owner.sales') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 ml-2">Logout</button>
                    </form>
                </div>
                <div class="lg:hidden flex items-center">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-slate-600 hover:text-pharmacy-600 focus:outline-none p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="{{ route('owner.inventory') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Inventory</a>
                <a href="{{ route('owner.reservations') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-pharmacy-600 bg-pharmacy-50 hover:bg-pharmacy-100 transition">📋 Reservations</a>
                <a href="{{ route('owner.sellers') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">👥 Sellers</a>
                <a href="{{ route('owner.sales') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-semibold text-red-500 hover:bg-red-50 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Pharmacy Reservations</h1>
            <p class="text-sm text-slate-500">View all customer reservations for your pharmacy.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-xs font-semibold uppercase tracking-wider bg-slate-50/70">
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Client</th>
                            <th class="py-4 px-6 text-center">Code</th>
                            <th class="py-4 px-6 text-center">Qty</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6">Seller</th>
                            <th class="py-4 px-6 text-right">Reserved / Expires</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-800">{{ $reservation->product_name }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">ID: {{ $reservation->_id }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium text-slate-700">{{ $reservation->client_name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center font-mono font-black text-emerald-600 tracking-widest text-lg">
                                    {{ $reservation->confirmation_code }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    {{ $reservation->quantity ?? 1 }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        @if($reservation->status === 'completed') bg-emerald-100 text-emerald-800
                                        @elseif($reservation->status === 'pending') bg-amber-100 text-amber-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $reservation->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($reservation->status === 'completed' && $reservation->seller_id)
                                        @php $seller = \App\Models\User::find($reservation->seller_id); @endphp
                                        <span class="text-xs text-slate-600">{{ $seller ? $seller->prenom . ' ' . $seller->nom : 'N/A' }}</span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right font-mono text-xs text-slate-500">
                                    <div>{{ $reservation->created_at ? $reservation->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    <div class="text-slate-400">Exp: {{ $reservation->expires_at ? (is_string($reservation->expires_at) ? \Carbon\Carbon::parse($reservation->expires_at)->format('d/m/Y H:i') : $reservation->expires_at->format('d/m/Y H:i')) : '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <p class="text-sm font-semibold">No reservations found for your pharmacy.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>