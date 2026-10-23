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
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain"> <span>PharmaPortal</span>
                </a>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="/client-categories" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">Categories</a>
                    <a href="/client-nearme" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Near Me</a>
                    <a href="{{ route('client.orders') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">My Orders</a>

                    <div class="relative group inline-block text-left">
                        <button class="w-10 h-10 rounded-xl bg-pharmacy-100 hover:bg-pharmacy-200 text-pharmacy-700 flex items-center justify-center font-bold transition focus:outline-none">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </button>

                        <div class="hidden group-focus-within:block hover:block absolute right-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Signed in as</p>
                                <p class="text-sm font-bold text-slate-700 truncate">{{ Auth::user()->prenom ?? 'Client' }}</p>
                            </div>
                            <a href="{{ route('client.account_settings') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Account Settings</a>
                            <hr class="border-slate-100 my-1">
                            <form action="/logout" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-medium transition">🚪 Sign Out</button>
                            </form>
                        </div>
                    </div>
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
                <div class="px-3 py-2 border-b border-slate-100 mb-2">
                    <p class="text-xs text-slate-400">Signed in as</p>
                    <p class="text-sm font-bold text-slate-700 truncate">{{ Auth::user()->prenom ?? 'Client' }}</p>
                </div>
                <a href="/client-categories" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">Categories</a>
                <a href="/client-nearme" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> Near Me</a>
                <a href="{{ route('client.orders') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">My Orders</a>
                <a href="{{ route('client.account_settings') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Account Settings</a>
                <form action="/logout" method="POST" class="block w-full mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base text-red-600 hover:bg-red-50 font-medium transition">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Prescription Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Search, evaluate, and reserve pharmacy stock instantly.</p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-8">
            <form action="/client-dashboard" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
                    <input type="text" name="search" id="search-input" autocomplete="off" value="{{ request('search') }}" placeholder="Search by medication name or description..."
                           class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-sm focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition duration-150">
                    
                    <div id="suggestions-box" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg hidden z-50 overflow-hidden">
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition shrink-0 shadow-md shadow-emerald-600/10">
                    Search
                </button>
                @if(request('search'))
                    <a href="/client-dashboard" class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition text-center flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </form>
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
                        searchInput.form.submit();
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
</script>


        @if($products->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="text-4xl mb-3"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                <h3 class="text-lg font-bold text-slate-800">No Medications Found</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">We couldn't find any matches for "{{ request('search') }}".</p>
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
                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $product->description ?? 'No description available.' }}</p>
                        </div>

                        <div>
                            <div class="mb-4 p-3 bg-slate-50 rounded-xl">
                                <div class="text-xl font-black text-pharmacy-600">{{ $product->price }} DA</div>
                                <div class="text-xs text-slate-500 mt-1">Status:
                                    <span class="{{ $product->stock > 0 ? 'font-bold text-emerald-600' : 'font-bold text-red-500' }}">
                                        {{ $product->stock > 0 ? 'Available' : 'Out of Stock' }}
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