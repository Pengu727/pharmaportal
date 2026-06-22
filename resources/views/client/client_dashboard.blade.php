<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Medications - PharmaPortal</title>
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
                
                <div class="flex items-center space-x-4">
                    <a href="/client-categories" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">Categories</a>
                    <a href="?orders=history" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">Orders</a>
                    
                    <div class="relative group inline-block text-left">
                        <button class="w-10 h-10 rounded-xl bg-pharmacy-100 hover:bg-pharmacy-200 text-pharmacy-700 flex items-center justify-center font-bold transition focus:outline-none">
                            👤
                        </button>
                        
                        <div class="hidden group-focus-within:block hover:block absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Signed in as</p>
                                <p class="text-sm font-bold text-slate-700 truncate">{{ Auth::user()->prenom ?? 'Client' }}</p>
                            </div>
                            <a href="{{ route('client.account_settings') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-pharmacy-600 transition">⚙️ Account Settings</a>
                            <hr class="border-slate-100 my-1">
                            <form action="/logout" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition">🚪 Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Prescription Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Search, evaluate, and hold pharmacy stock instantly.</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <form action="/client-dashboard" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by medication name, substance molecule, or category..." 
                           class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                </div>
                <button type="submit" class="px-6 py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition shrink-0 shadow-md shadow-emerald-600/10">
                    Search Catalog
                </button>
                @if(request('search'))
                    <a href="/client-dashboard" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition text-center flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>
        

        @if($products->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="text-lg font-bold text-slate-800">No Medications Found</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">We couldn't find any registered pharmacy matches for "{{ request('search') }}".</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div class="mb-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    {{ $product->category ?? 'General' }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 mb-1 hover:text-pharmacy-600 transition">
                                <a href="{{ route('client.product_view', ['id' => $product->_id]) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $product->description ?? 'No prescription description details documented.' }}</p>
                        </div>
                        
                        <div>
                            <div class="mb-4 p-3 bg-slate-50 rounded-xl">
                                <div class="text-xl font-black text-pharmacy-600">{{ $product->price }} DA</div>
                                <div class="text-xs text-slate-500 mt-1">Stock Availability: 
                                    <span class="{{ $product->stock > 0 ? 'font-bold text-slate-700' : 'font-bold text-red-500' }}">
                                        {{ $product->stock > 0 ? $product->stock . ' units' : 'Out of Stock' }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('client.product_view', ['id' => $product->_id]) }}" 
                               class="w-full py-2.5 font-bold rounded-xl text-xs transition text-center block shadow-sm {{ $product->stock > 0 ? 'bg-pharmacy-600 hover:bg-pharmacy-700 text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed pointer-events-none' }}">
                                {{ $product->stock > 0 ? 'View Details & Reserve' : 'Out of Stock' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->appends(['search' => request('search')])->links() }}
            </div>
        @endif
    </div>

</body>
</html>