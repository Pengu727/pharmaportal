<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller POS - PharmaPortal</title>
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
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-blue-600 font-mono bg-blue-50 px-2 py-0.5 rounded border border-blue-500/10 ml-1">Seller Desk</span></span>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('seller.inventory') }}" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> POS</a>
                    <a href="{{ route('seller.reservations') }}" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition">📋 Reservations</a>
                    <a href="{{ route('seller.sales') }}" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</button>
                    </form>
                    <div class="w-9 h-9 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ substr(auth()->user()->prenom ?? 'S', 0, 1) }}
                    </div>
                </div>
                <div class="lg:hidden flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ substr(auth()->user()->prenom ?? 'S', 0, 1) }}
                    </div>
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
                <a href="{{ route('seller.inventory') }}" class="block px-3 py-2 rounded-lg text-base font-bold text-pharmacy-600 bg-pharmacy-50 hover:bg-pharmacy-100 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> POS</a>
                <a href="{{ route('seller.reservations') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">📋 Reservations</a>
                <a href="{{ route('seller.sales') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                <form action="/logout" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-semibold text-red-500 hover:bg-red-50 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        {{-- Reservation Claim Section --}}
        <div class="max-w-md bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <h2 class="font-black text-slate-800 text-base mb-1">Process Reservation Claim</h2>
            <p class="text-xs text-slate-400 mb-4">Enter the customer's 6-digit confirmation code to finalize collection.</p>

            @if(session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('seller.reservations.claim') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                @csrf
                <input type="text" name="confirmation_code" maxlength="6" required placeholder="000000"
                       class="w-full sm:flex-1 text-center font-mono font-bold tracking-widest px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-pharmacy-600 transition">
                <button type="submit" class="w-full sm:w-auto bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition active:scale-95 text-center">
                    Claim Code
                </button>
            </form>
        </div>

        {{-- Seller Alert Cards (Read-Only) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
            <a href="?filter=low_stock" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'low_stock' ? 'border-amber-500 ring-2 ring-amber-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">Low Stock</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $alerts['low_stock'] ?? 0 }}</p>
            </a>
            <a href="?filter=expiring_soon" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'expiring_soon' ? 'border-orange-500 ring-2 ring-orange-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-orange-600 font-bold uppercase tracking-wider">Expiring Soon</p>
                <p class="text-2xl font-black text-orange-600 mt-1">{{ $alerts['expiring_soon'] ?? 0 }}</p>
            </a>
            <a href="?filter=expired" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'expired' ? 'border-red-500 ring-2 ring-red-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-red-600 font-bold uppercase tracking-wider">Expired</p>
                <p class="text-2xl font-black text-red-600 mt-1">{{ $alerts['expired'] ?? 0 }}</p>
            </a>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Counter POS</h1>
                <p class="text-xs text-slate-400 mt-0.5">View inventory and sell medications to walk-in customers</p>
            </div>

            <form action="/seller/inventory" method="GET" class="w-full md:w-72">
                <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search ?? request('search') }}" placeholder="Search medications..."
                           class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-pharmacy-600 focus:ring-4 focus:ring-pharmacy-600/5 transition">
                    <div class="absolute left-3 top-2.5 text-slate-400"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></div>
                </div>
            </form>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php $currentFilter = $filter ?? 'all'; @endphp
            @foreach(['all' => 'All', 'available' => 'Available', 'low_stock' => 'Low Stock', 'expiring_soon' => 'Expiring Soon', 'expired' => 'Expired'] as $key => $label)
                <a href="?filter={{ $key }}{{ !empty($search ?? '') ? '&search=' . urlencode($search) : '' }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $currentFilter === $key ? 'bg-pharmacy-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $p)
                @php $status = $p->status; @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-slate-300 transition duration-200">
                    <div class="p-5 flex-1 flex flex-col">
                        {{-- Status Badge --}}
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide
                                @if($status === 'ACTIVE') bg-emerald-100 text-emerald-800
                                @elseif($status === 'LOW_STOCK') bg-amber-100 text-amber-800
                                @elseif($status === 'EXPIRING_SOON') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                            @if(($p->visibility_status ?? 'public') === 'private')
                                <span class="text-[9px] font-bold text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">🔒 Private</span>
                            @endif
                        </div>

                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-slate-800 group-hover:text-pharmacy-600 transition text-base">{{ $p->name }}</h3>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $p->category ?? 'General' }}</p>
                            </div>
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold font-mono tracking-tight {{ $p->stock > ($p->min_threshold ?? 10) ? 'bg-emerald-50 text-emerald-700' : ($p->stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                {{ $p->stock }} left
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-3 flex-1">
                            {{ $p->description ?? 'No description provided.' }}
                        </p>

                        {{-- Expiration info --}}
                        @if($p->expiration_date)
                            <p class="text-[10px] font-mono mb-3 {{ $status === 'EXPIRED' ? 'text-red-600 font-bold' : ($status === 'EXPIRING_SOON' ? 'text-orange-600 font-bold' : 'text-slate-400') }}">
                                Exp: {{ $p->expiration_date->format('d/m/Y') }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between mb-4 pt-3 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400">Price</p>
                                <p class="text-lg font-bold text-slate-800">{{ number_format($p->price, 2) }} DA</p>
                            </div>
                        </div>

                        @if($p->stock > 0)
                            <form action="/seller/inventory/{{ $p->_id }}/decrement" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" max="{{ $p->stock }}" required
                                       class="w-16 px-2 py-2.5 text-center font-bold bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-pharmacy-600 transition">
                                <button type="submit" class="flex-1 px-4 py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm rounded-lg transition active:scale-95">
                                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><polyline points="20 6 9 17 4 12"></polyline></svg> Sell
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full px-4 py-2.5 bg-slate-200 text-slate-400 font-bold text-sm rounded-lg cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <p class="text-slate-500 text-sm">{{ ($filter ?? 'all') !== 'all' ? 'No products match this filter.' : 'No products available.' }}</p>
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>