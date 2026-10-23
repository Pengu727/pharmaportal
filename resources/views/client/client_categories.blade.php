<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Categories - PharmaPortal</title>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        @if(empty($selectedCategory))
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Medication Categories</h1>
                <p class="text-sm text-slate-500 mt-1">Select a therapeutic category field to display target stock records.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $cat)
                    <a href="?category={{ urlencode($cat) }}" 
                       class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-pharmacy-600 hover:shadow-md transition text-left group flex flex-col justify-between h-36">
                        <div>
                            <div class="w-10 h-10 bg-pharmacy-50 text-pharmacy-600 rounded-xl flex items-center justify-center text-lg mb-3 font-bold group-hover:bg-pharmacy-600 group-hover:text-white transition">
                                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M10.5 20.5l-6-6a4.95 4.95 0 1 1 7-7l6 6a4.95 4.95 0 1 1-7 7z"></path><line x1="8.5" y1="8.5" x2="15.5" y2="15.5"></line></svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-800 group-hover:text-pharmacy-700 transition truncate">{{ $cat }}</h3>
                        </div>
                        <span class="text-xs font-semibold text-pharmacy-600 flex items-center gap-1">
                            Browse Products <span>→</span>
                        </span>
                    </a>
                @endforeach
            </div>

        @else
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-4 gap-4 sm:gap-0">
                <div>
                    <span class="text-xs font-bold text-pharmacy-600 uppercase tracking-wider">Category Catalog</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight mt-0.5">{{ $selectedCategory }}</h1>
                </div>
                <a href="/client-categories" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition self-start sm:self-auto">
                    View All Categories
                </a>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200 p-8">
                    <div class="text-4xl mb-3"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg></div>
                    <h3 class="text-lg font-bold text-slate-800">No Products Found</h3>
                    <p class="text-sm text-slate-400 mt-1">There are currently no medications mapped under the category "{{ $selectedCategory }}".</p>
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
                
                <div class="mt-10">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>

</body>
</html>