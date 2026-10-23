<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - PharmaPortal</title>
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
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Owner</span></span>
                </div>
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="/owner/inventory" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Inventory</a>
                    <a href="/owner/reservations" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition">📋 Reservations</a>
                    <a href="/owner/sellers" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition">👥 Sellers</a>
                    <a href="/owner/sales" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</button>
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
                <a href="/owner/inventory" class="block px-3 py-2 rounded-lg text-base font-bold text-pharmacy-600 bg-pharmacy-50 hover:bg-pharmacy-100 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Inventory</a>
                <a href="/owner/reservations" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">📋 Reservations</a>
                <a href="/owner/sellers" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition">👥 Sellers</a>
                <a href="/owner/sales" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:text-pharmacy-600 hover:bg-slate-50 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="12" cy="12" r="10"></circle><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path><path d="M12 18V6"></path></svg> Sales</a>
                <form action="/logout" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-base font-semibold text-red-500 hover:bg-red-50 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-500/20 text-emerald-700 rounded-xl text-sm font-medium">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        {{-- Dashboard Alert Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
            <a href="?filter=all" class="bg-white p-4 rounded-xl border {{ ($filter ?? 'all') === 'all' ? 'border-pharmacy-600 ring-2 ring-pharmacy-600/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $alerts['total'] }}</p>
            </a>
            <a href="?filter=public" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'public' ? 'border-pharmacy-600 ring-2 ring-pharmacy-600/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Public</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $alerts['public'] }}</p>
            </a>
            <a href="?filter=private" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'private' ? 'border-pharmacy-600 ring-2 ring-pharmacy-600/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Private</p>
                <p class="text-2xl font-black text-slate-600 mt-1">{{ $alerts['private'] }}</p>
            </a>
            <a href="?filter=low_stock" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'low_stock' ? 'border-amber-500 ring-2 ring-amber-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">Low Stock</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $alerts['low_stock'] }}</p>
            </a>
            <a href="?filter=expiring_soon" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'expiring_soon' ? 'border-orange-500 ring-2 ring-orange-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-orange-600 font-bold uppercase tracking-wider">Expiring Soon</p>
                <p class="text-2xl font-black text-orange-600 mt-1">{{ $alerts['expiring_soon'] }}</p>
            </a>
            <a href="?filter=expired" class="bg-white p-4 rounded-xl border {{ ($filter ?? '') === 'expired' ? 'border-red-500 ring-2 ring-red-500/10' : 'border-slate-200' }} shadow-sm hover:shadow-md transition">
                <p class="text-[10px] text-red-600 font-bold uppercase tracking-wider">Expired</p>
                <p class="text-2xl font-black text-red-600 mt-1">{{ $alerts['expired'] }}</p>
            </a>
        </div>

        {{-- Header + Search + Filter --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pharmacy Inventory</h1>
                <p class="text-xs text-slate-400 mt-0.5">Manage your product catalog, stock levels, and expiration dates</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                <form action="/owner/inventory" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products..."
                           class="flex-1 sm:flex-none px-4 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:border-pharmacy-600 transition w-full sm:w-48">
                    <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-bold text-slate-700 transition"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                </form>
                <button onclick="document.getElementById('addForm').classList.toggle('hidden')" class="w-full sm:w-auto justify-center bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition shadow-md shadow-pharmacy-600/10 flex items-center space-x-2">
                    <span><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span> <span>Add Product</span>
                </button>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php $currentFilter = $filter ?? 'all'; @endphp
            @foreach(['all' => 'All', 'public' => 'Public', 'private' => 'Private', 'available' => 'Available', 'low_stock' => 'Low Stock', 'expiring_soon' => 'Expiring Soon', 'expired' => 'Expired'] as $key => $label)
                <a href="?filter={{ $key }}{{ !empty($search ?? '') ? '&search=' . urlencode($search) : '' }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $currentFilter === $key ? 'bg-pharmacy-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Add Product Form --}}
        <div id="addForm" class="hidden mb-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Add New Product</h2>
            <form action="/owner/inventory" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Product Name *</label>
                        <input name="name" placeholder="e.g., Paracetamol 500mg" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Category</label>
                        <input name="category" placeholder="e.g., Antibiotics, Painkillers" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Price (DA) *</label>
                        <input name="price" type="number" step="0.01" placeholder="0.00" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Stock Quantity *</label>
                        <input name="stock" type="number" placeholder="0" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Min. Threshold</label>
                        <input name="min_threshold" type="number" placeholder="10" value="10" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Expiration Date *</label>
                        <input name="expiration_date" type="date" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Dosage</label>
                        <input name="dosage" placeholder="e.g., 500mg, 10ml" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Laboratory</label>
                        <input name="laboratory" placeholder="e.g., Sanofi, Pfizer" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Visibility</label>
                        <select name="visibility_status" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                            <option value="public">🟢 Public (visible to clients)</option>
                            <option value="private">🔒 Private (internal only)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">SKU</label>
                        <input name="sku" placeholder="Optional SKU" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" placeholder="Product details..." rows="2" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white transition"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 mt-4">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm rounded-lg transition">Save Product</button>
                    <button type="button" onclick="document.getElementById('addForm').classList.add('hidden')" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Products Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-5">Product Name</th>
                            <th class="py-4 px-5">Category</th>
                            <th class="py-4 px-5">Price</th>
                            <th class="py-4 px-5">Stock</th>
                            <th class="py-4 px-5">Expiration</th>
                            <th class="py-4 px-5">Status</th>
                            <th class="py-4 px-5">Visibility</th>
                            <th class="py-4 px-5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($products as $p)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-5">
                                    <span class="font-bold text-slate-800">{{ $p->name }}</span>
                                    @if($p->description)
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Illuminate\Support\Str::limit($p->description, 40) }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">{{ $p->category ?? 'General' }}</span>
                                </td>
                                <td class="py-3.5 px-5 font-bold text-slate-800">{{ number_format($p->price, 2) }} DA</td>
                                <td class="py-3.5 px-5">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold {{ $p->stock > ($p->min_threshold ?? 10) ? 'bg-emerald-100 text-emerald-700' : ($p->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $p->stock }} / {{ $p->min_threshold ?? 10 }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-xs font-mono text-slate-600">
                                    {{ $p->expiration_date ? $p->expiration_date->format('d/m/Y') : '—' }}
                                </td>
                                <td class="py-3.5 px-5">
                                    @php $status = $p->status; @endphp
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                        @if($status === 'ACTIVE') bg-emerald-100 text-emerald-800
                                        @elseif($status === 'LOW_STOCK') bg-amber-100 text-amber-800
                                        @elseif($status === 'EXPIRING_SOON') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5">
                                    @if(($p->visibility_status ?? 'public') === 'public')
                                        <span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">🟢 Public</span>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">🔒 Private</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="/owner/inventory/{{ $p->_id }}/edit" class="px-3 py-1.5 bg-pharmacy-50 hover:bg-pharmacy-600 text-pharmacy-700 hover:text-white font-bold text-xs rounded-lg transition border border-pharmacy-500/10">
                                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                                        </a>
                                        <form action="{{ route('owner.inventory.destroy', ['id' => $p->_id]) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white font-bold text-xs rounded-lg transition border border-red-500/10">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500">
                                    <p class="text-sm">{{ ($filter ?? 'all') !== 'all' ? 'No products match this filter.' : 'No products yet. Add your first product to get started.' }}</p>
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