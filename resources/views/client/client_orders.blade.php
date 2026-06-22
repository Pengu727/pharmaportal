<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - PharmaPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pharmacy: { 50: '#f0fdf4', 100: '#dcfce7', 600: '#059669', 700: '#047857' } } } }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased pb-12">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center relative">
                <div class="flex items-center space-x-3">
                    <a href="/client-dashboard" class="cursor-pointer text-2xl flex items-center space-x-2">
                        <span>💚</span> 
                        <span class="font-bold text-xl text-slate-800">PharmaPortal</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/client-dashboard" class="text-sm font-bold text-slate-500 hover:text-slate-700">Back to Catalog</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 pt-10">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Hold Bookings & Reservations</h1>
            <p class="text-xs text-slate-500 mt-1">Track over-the-counter medication hold verifications booked under your client profile.</p>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-medium rounded-xl shadow-sm">
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($reservations as $reservation)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center space-x-2.5 mb-1.5">
                            <span class="font-bold text-slate-800 text-sm font-mono tracking-wider">CODE: #{{ $reservation->confirmation_code }}</span>
                            
                            @if($reservation->status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">Pending Hold</span>
                            @elseif($reservation->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wide">Ready for Pickup</span>
                            @elseif($reservation->status === 'claimed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Claimed</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 uppercase tracking-wide">Expired</span>
                            @endif
                        </div>
                        
                        <p class="text-xs text-slate-500">
                            Booked: {{ $reservation->created_at ? $reservation->created_at->format('M d, Y H:i') : 'N/A' }} 
                            • Location: <span class="font-medium text-slate-600">{{ $reservation->pharmacy_name ?? 'Local Dispensary' }}</span>
                        </p>
                        
                        <p class="text-sm font-bold text-slate-700 mt-2">{{ $reservation->product_name }}</p>
                    </div>

                    <div class="text-left sm:text-right w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 flex sm:flex-col justify-between sm:justify-center items-center sm:items-end">
                        <span class="text-xs text-slate-400 block sm:hidden">Action Windows</span>
                        
                        @if($reservation->status === 'pending')
                            <form action="/reservations/{{ $reservation->_id ?? $reservation->id }}/confirm" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 bg-pharmacy-600 hover:bg-pharmacy-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                    Confirm Order
                                </button>
                            </form>
                        @elseif($reservation->status === 'confirmed')
                            <div class="text-left sm:text-right">
                                <span class="block text-[9px] font-bold text-blue-600 uppercase tracking-wider">Pickup Passcode</span>
                                <span class="text-base font-black text-slate-800 tracking-widest font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-200 block mt-0.5">
                                    {{ $reservation->confirmation_code }}
                                </span>
                            </div>
                        @else
                            <span class="text-xs font-semibold text-slate-400 italic capitalize">Processed ({{ $reservation->status }})</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-12 text-center">
                    <span class="text-4xl block mb-3">📦</span>
                    <h3 class="text-sm font-bold text-slate-700">No Reservations Found</h3>
                    <p class="text-xs text-slate-400 mt-1">You haven't requested any over-the-counter medicine inventory locks yet.</p>
                    <a href="/client-dashboard" class="inline-block mt-4 text-xs font-bold text-pharmacy-600 hover:underline">Browse Catalog →</a>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>