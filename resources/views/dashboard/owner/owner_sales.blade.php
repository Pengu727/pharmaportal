<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History - PharmaPortal Owner</title>
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
                    <a href="{{ route('owner.reservations') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">📋 Reservations</a>
                    <a href="{{ route('owner.sellers') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">👥 Sellers</a>
                    <a href="{{ route('owner.sales') }}" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
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
                <a href="{{ route('owner.reservations') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">📋 Reservations</a>
                <a href="{{ route('owner.sellers') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">👥 Sellers</a>
                <a href="{{ route('owner.sales') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-pharmacy-600 bg-pharmacy-50 hover:bg-pharmacy-100 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-semibold text-red-500 hover:bg-red-50 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sales History</h1>
            <p class="text-xs text-slate-400 mt-0.5">Track completed sales from reservations processed by your sellers</p>
        </div>

        @if($sales->count() > 0)
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Sales</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $sales->count() }}</p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Revenue</p>
                    <p class="text-3xl font-black text-pharmacy-600 mt-1">{{ number_format($sales->sum(fn($s) => ($s->price ?? 0) * ($s->quantity ?? 1)), 2) }} <span class="text-base">DA</span></p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Unique Sellers</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ $sales->unique('seller_id')->count() }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-xs font-semibold uppercase tracking-wider bg-slate-50/70">
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Sale Type</th>
                            <th class="py-4 px-6">Client</th>
                            <th class="py-4 px-6">Seller</th>
                            <th class="py-4 px-6 text-center">Qty</th>
                            <th class="py-4 px-6 text-right">Price</th>
                            <th class="py-4 px-6 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ $sale->product_name }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium {{ $sale->reservation_id ? 'text-blue-600' : 'text-emerald-600' }}">
                                        {{ $sale->reservation_id ? 'Reservation' : 'Manual' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium text-slate-600">{{ $sale->reservation_id ? ($sale->client_name ?? 'N/A') : '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-medium text-slate-600">{{ $sale->seller_name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center font-bold">{{ $sale->quantity ?? 1 }}</td>
                                <td class="py-4 px-6 text-right font-bold text-slate-800">{{ number_format(($sale->price ?? 0) * ($sale->quantity ?? 1), 2) }} DA</td>
                                <td class="py-4 px-6 text-right font-mono text-xs text-slate-500">
                                    {{ $sale->sold_at ? (is_string($sale->sold_at) ? \Carbon\Carbon::parse($sale->sold_at)->format('d/m/Y H:i') : $sale->sold_at->format('d/m/Y H:i')) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-400">
                                    <p class="text-sm font-semibold">No sales recorded yet. Sales are generated when sellers perform manual sales or complete reservations.</p>
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
