<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - PharmaPortal</title>
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
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Owner</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/owner/inventory" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1">📦 Inventory</a>
                    <a href="/owner/reservations" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition">📋 Reservations</a>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</button>
                    </form>
                    <div class="w-9 h-9 bg-pharmacy-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ substr(session('user_name', 'O'), 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pharmacy Inventory</h1>
                <p class="text-xs text-slate-400 mt-0.5">Manage your product catalog and stock levels</p>
            </div>
            <button onclick="document.getElementById('addForm').classList.toggle('hidden')" class="bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition shadow-md shadow-pharmacy-600/10 flex items-center space-x-2">
                <span>➕</span> <span>Add Product</span>
            </button>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-500/20 text-emerald-700 rounded-xl text-sm font-medium">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        <div id="addForm" class="hidden mb-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Add New Product</h2>
            <form action="/owner/inventory" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Product Name</label>
                        <input name="name" placeholder="e.g., Paracetamol 500mg" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">SKU</label>
                        <input name="sku" placeholder="Optional SKU" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Price (DA)</label>
                        <input name="price" type="number" step="0.01" placeholder="0.00" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Stock Quantity</label>
                        <input name="stock" type="number" placeholder="0" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" placeholder="Product details..." rows="2" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm rounded-lg transition">Save Product</button>
                    <button type="button" onclick="document.getElementById('addForm').classList.add('hidden')" class="px-4 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition">Cancel</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Product Name</th>
                            <th class="py-4 px-6">SKU</th>
                            <th class="py-4 px-6">Price (DA)</th>
                            <th class="py-4 px-6">Stock</th>
                            <th class="py-4 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($products as $p)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ $p->name }}</span>
                                    @if($p->description)
                                        <p class="text-xs text-slate-400 mt-1">{{ substr($p->description, 0, 50) }}...</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-mono text-xs text-slate-600">{{ $p->sku ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-slate-800">{{ number_format($p->price, 2) }}</span>
                                }</td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $p->stock > 20 ? 'bg-emerald-100 text-emerald-700' : ($p->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $p->stock }} units
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="/owner/inventory/{{ $p->_id }}/edit" class="px-3 py-1.5 bg-pharmacy-50 hover:bg-pharmacy-600 text-pharmacy-700 hover:text-white font-bold text-xs rounded-lg transition border border-pharmacy-500/10">
                                            ✏️ Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    <p class="text-sm">No products yet. Add your first product to get started.</p>
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