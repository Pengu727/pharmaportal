<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller POS - PharmaPortal</title>
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
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">💚</span>
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-blue-600 font-mono bg-blue-50 px-2 py-0.5 rounded border border-blue-500/10 ml-1">Seller Desk</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</button>
                    </form>
                    <div class="w-9 h-9 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        <div class="max-w-md bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <h2 class="font-black text-slate-800 text-base mb-1">Process Reservation Claim</h2>
            <p class="text-xs text-slate-400 mb-4">Enter the customer's 6-digit confirmation pin code below to verify and finalize hold collection.</p>

            @if(session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('seller.reservations.claim') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="confirmation_code" maxlength="6" required placeholder="000000" 
                       class="flex-1 text-center font-mono font-bold tracking-widest px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-pharmacy-600 transition">
                <button type="submit" class="bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition active:scale-95">
                    Claim Code
                </button>
            </form>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Counter POS</h1>
                <p class="text-xs text-slate-400 mt-0.5">Quickly manage and sell medication directly to customers</p>
            </div>
            
            <form action="/seller/inventory" method="GET" class="w-full md:w-72">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search medications..." 
                           class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-pharmacy-600 focus:ring-4 focus:ring-pharmacy-600/5 transition">
                    <div class="absolute left-3 top-2.5 text-slate-400">🔍</div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $p)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:border-slate-300 transition duration-200">
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-slate-800 group-hover:text-pharmacy-600 transition text-base">{{ $p->name }}</h3>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">ID: {{ $p->_id }}</p>
                            </div>
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold font-mono tracking-tight {{ $p->stock > 10 ? 'bg-emerald-50 text-emerald-700' : ($p->stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                {{ $p->stock }} left
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 mb-4 flex-1">
                            {{ $p->description ?? 'No description provided for this medication.' }}
                        </p>

                        <div class="flex items-center justify-between mb-4 pt-3 border-t border-slate-100">
                            <div>
                                <p class="text-xs text-slate-400">Price</p>
                                <p class="text-lg font-bold text-slate-800">{{ number_format($p->price, 2) }} DA</p>
                            </div>
                        </div>

                        @if($p->stock > 0)
                            <form action="/seller/inventory/{{ $p->_id }}/decrement" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm rounded-lg transition active:scale-95">
                                    ✓ Sell Item
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
                    <p class="text-slate-500 text-sm">No products available at this time.</p>
                </div>
            @endforelse
        </div>

    </main>

</body>
</html>