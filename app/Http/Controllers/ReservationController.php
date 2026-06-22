<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Create reservation (Pure HTML Form Submission)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'pharmacy_id' => 'required|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Generate 6-digit code
        $confirmationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'pharmacy_id' => $request->pharmacy_id,
            'product_name' => $product->name,
            'pharmacy_name' => $product->pharmacy_name,
            'confirmation_code' => $confirmationCode,
            'status' => 'pending', // pending, confirmed, expired, claimed
            'created_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);

        // Redirect directly to the orders page with a native flash message
        return redirect()->route('client.orders')->with('status', 'Reservation hold secured successfully for 24 hours!');
    }

    // Get user's reservations
    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark expired reservations
        foreach ($reservations as $res) {
            if ($res->status === 'pending' && $res->expires_at < now()) {
                $res->update(['status' => 'expired']);
            }
        }

        // Points to resources/views/client/client_orders.blade.php
        return view('client.client_orders', ['reservations' => $reservations]);
    }

    // Confirm reservation at pharmacy (Pure HTML Form Submission)
    public function confirm(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($reservation->expires_at < now()) {
            $reservation->update(['status' => 'expired']);
            return redirect()->back()->with('error', 'This reservation has expired.');
        }

        $reservation->update(['status' => 'confirmed']);

        return redirect()->back()->with('status', 'Reservation confirmed successfully! Your pickup code is now active.');
    }

    // Seller claims reservation (Pure HTML Form Submission)
    public function claim(Request $request)
    {
        $request->validate(['confirmation_code' => 'required|string|size:6']);

        $reservation = Reservation::where('confirmation_code', $request->confirmation_code)
            ->where('status', 'confirmed')
            ->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Invalid or inactive verification code.');
        }

        if ($reservation->expires_at < now()) {
            $reservation->update(['status' => 'expired']);
            return redirect()->back()->with('error', 'This reservation hold has expired.');
        }

        $reservation->update(['status' => 'claimed']);

        return redirect()->back()->with('status', 'Medication inventory hold successfully claimed and dispatched over the counter.');
    }
}