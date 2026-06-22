<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - PharmaPortal</title>
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
                <a href="/owner/inventory" class="text-sm font-semibold text-slate-700 hover:text-pharmacy-600 transition">← Back to Inventory</a>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Product</h1>
            <p class="text-xs text-slate-400 mt-0.5">Update product details and inventory levels</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        <form action="/owner/inventory/{{ $product->_id }}/update" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Product Name</label>
                    <input name="name" value="{{ $product->name }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">SKU</label>
                        <input name="sku" value="{{ $product->sku }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Price (DA)</label>
                        <input name="price" type="number" step="0.01" value="{{ $product->price }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Stock Quantity</label>
                    <input name="stock" type="number" value="{{ $product->stock }}" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-slate-800 bg-slate-50 focus:outline-none focus:border-pharmacy-600 focus:bg-white focus:ring-4 focus:ring-pharmacy-600/10 transition">{{ $product->description }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-sm rounded-lg transition shadow-md shadow-pharmacy-600/10">Save Changes</button>
                <a href="/owner/inventory" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition">Cancel</a>
            </div>
        </form>

    </main>

</body>
</html>