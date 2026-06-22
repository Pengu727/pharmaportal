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
                <a href="/" class="flex items-center space-x-2 text-xl font-bold text-slate-800">
                    <span>💚</span> <span>PharmaPortal</span>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('client.login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-pharmacy-600">Log In</a>
                    <a href="{{ route('client.register') }}" class="px-4 py-2 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-lg text-sm transition">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
       <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <h1 class="text-xl font-bold text-slate-800 mb-2">Search Medications Across Algiers</h1>
    <p class="text-sm text-slate-500 mb-4">Enter medication name or keywords to discover active pharmacy stocks.</p>
    
    <div class="relative">
        <form action="{{ route('guest.products') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" id="search-input" autocomplete="off" value="{{ request('search') }}" placeholder="Search (e.g., Paracetamol, Amoxicillin...)" 
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-pharmacy-600 focus:border-transparent transition">
            <button type="submit" class="px-6 py-3 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-sm transition shadow-sm">
                Search
            </button>
        </form>

        <div id="suggestions-box" class="absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg hidden z-50 overflow-hidden">
            </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('suggestions-box');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.add('hidden');
            return;
        }

        // Fetch suggestions from our new backend route
        fetch(`/products/suggest?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsBox.innerHTML = '';

                if (data.length === 0) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }

                // Render each suggestion row
                data.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 cursor-pointer transition font-medium border-b border-slate-50 last:border-0';
                    div.textContent = product.name;
                    
                    // Clicking on a suggestion fills the input and submits immediately
                    div.addEventListener('click', function() {
                        searchInput.value = product.name;
                        suggestionsBox.classList.add('hidden');
                        searchInput.form.submit();
                    });

                    suggestionsBox.appendChild(div);
                });

                suggestionsBox.classList.remove('hidden');
            });
    });

    // Hide dropdown if user clicks outside of the search box area
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.classList.add('hidden');
        }
    });
});
</script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if($products->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-200">
                <p class="text-slate-400 text-sm">No products found matching your search parameters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @php
                        $category = isset($product->metadata['category']) ? $product->metadata['category'] : 'General';
                    @endphp
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">{{ $category }}</span>
                                <span class="text-xs font-mono text-slate-400">{{ $product->sku ?? 'N/A' }}</span>
                            </div>
                            <h3 class="font-bold text-slate-800 mb-1 text-base line-clamp-1">{{ $product->name }}</h3>
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

                            <a href="{{ route('client.login') }}" class="w-full py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold rounded-xl text-xs transition text-center block shadow-sm">
                                Login to Reserve Securely
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