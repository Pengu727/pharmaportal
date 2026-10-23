<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" class="h-8 w-8 object-contain">
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Admin</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 transition">Logout</button>
                    </form>
                    <div class="w-9 h-9 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm" title="{{ session('admin_name', 'Admin') }}">
                        {{ substr(session('admin_name', 'A'), 0, 1) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-xl">{{ session('status') }}</div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Registered Pharmacies</h1>
                <p class="text-xs text-slate-400 mt-0.5">Manage pharmacies, owners, and core configurations</p>
            </div>

            <a href="{{ route('admin.create') }}" class="w-full sm:w-auto justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition shadow-md shadow-emerald-600/10 flex items-center space-x-2">
                <span><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span> <span>Add New Pharmacy</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Pharmacy Name</th>
                            <th class="py-4 px-6">Owner</th>
                            <th class="py-4 px-6">Location</th>
                            <th class="py-4 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                         @foreach($pharmacies as $p)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <a href="{{ route('admin.show', ['id' => (string)$p->_id]) }}" class="block font-bold text-slate-800 hover:text-emerald-600 transition">
                                        {{ $p->nom_pharmacie ?? 'Unnamed Pharmacy' }}
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-mono block">{{ $p->registre_commerce ?? 'No RC data' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($p->ownerUser)
                                        <span class="font-semibold text-slate-700">{{ $p->ownerUser->prenom }} {{ $p->ownerUser->nom }}</span>
                                        <span class="text-xs text-slate-400 block">{{ $p->ownerUser->email }}</span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No owner assigned</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    <span class="text-xs font-semibold text-slate-700 block">{{ $p->commune ?? '-' }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $p->wilaya ?? '-' }} • Algeria</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('admin.show', ['id' => (string)$p->_id]) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                            👁 View
                                        </a>
                                        <a href="{{ route('admin.edit', ['id' => (string)$p->_id]) }}" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white font-bold text-xs rounded-xl transition border border-emerald-500/10">
                                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg> Edit
                                        </a>
                                        <form action="{{ route('admin.destroy', ['id' => (string)$p->_id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pharmacy? This will also delete all associated accounts.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white font-bold text-xs rounded-xl transition border border-red-500/10">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>