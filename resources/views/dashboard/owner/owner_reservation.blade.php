<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Reservations Tracking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pharmacy: { 50: '#f0fdf4', 100: '#dcfce7', 600: '#059669', 700: '#047857' }
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
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal 
                        <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Owner Panel</span>
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.inventory') }}" class="text-sm font-semibold text-slate-600 hover:text-pharmacy-600 transition">📦 Manage Stock</a>
                    <a href="{{ route('owner.reservations') }}" class="text-sm font-bold text-pharmacy-600 border-b-2 border-pharmacy-600 pb-1">📋 Reservations Audit</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 ml-2">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-1">Global Pharmacy Reservation Log</h1>
            <p class="text-sm text-slate-500">Monitor active user medicine holds, cross-verify customer security pin logs, and analyze sales status distributions.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 text-xs font-semibold uppercase tracking-wider bg-slate-50/70">
                            <th class="py-4 px-6">Medication Details</th>
                            <th class="py-4 px-6 text-center">Verification Pin</th>
                            <th class="py-4 px-6 text-center">Current Status</th>
                            <th class="py-4 px-6 text-right">Expiration Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-800 text-base">{{ $reservation->product_name }}</div>
                                    <div class="text-xs text-slate-400 font-mono mt-0.5">Doc ID: {{ $reservation->_id }}</div>
                                </td>
                                <td class="py-4 px-6 text-center font-mono font-black text-emerald-600 tracking-widest text-lg bg-emerald-50/20">
                                    {{ $reservation->confirmation_code }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        @if($reservation->status === 'claimed') bg-emerald-100 text-emerald-800 
                                        @elseif($reservation->status === 'confirmed') bg-blue-100 text-blue-800 
                                        @elseif($reservation->status === 'pending') bg-amber-100 text-amber-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $reservation->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-medium text-slate-500 font-mono text-xs">
                                    {{ is_string($reservation->expires_at) ? \Carbon\Carbon::parse($reservation->expires_at)->format('d/m/Y H:i') : $reservation->expires_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-slate-400">
                                    <p class="text-sm font-semibold">No patient product holds have been booked under this store profile yet.</p>
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