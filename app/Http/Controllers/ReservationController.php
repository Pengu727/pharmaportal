<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Product;
use App\Models\Pharmacy;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Client creates a reservation. Stock is IMMEDIATELY decremented.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'pharmacy_id' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $pharmacy = Pharmacy::find($request->pharmacy_id);
        $quantity = (int) ($request->quantity ?? 1);

        // Check stock availability
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        // Generate unique 6-digit confirmation code
        $confirmationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = auth()->user();

        $reservation = Reservation::create([
            'user_id' => (string) $user->_id,
            'client_name' => $user->prenom . ' ' . $user->nom,
            'product_id' => $request->product_id,
            'product_name' => $product->name,
            'pharmacy_id' => $request->pharmacy_id,
            'pharmacy_name' => $pharmacy ? $pharmacy->nom_pharmacie : 'Unknown',
            'confirmation_code' => $confirmationCode,
            'quantity' => $quantity,
            'status' => 'pending',
            'created_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);

        // IMMEDIATELY decrement stock
        $product->stock = max(0, $product->stock - $quantity);
        $product->save();

        return redirect()->route('client.orders')
            ->with('status', "Reservation created! Your 6-digit code is: {$confirmationCode}. Valid for 24 hours.");
    }

    /**
     * Client views their reservations.
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', (string) auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark expired reservations and restore stock
        foreach ($reservations as $res) {
            if ($res->status === 'pending' && $res->expires_at && $res->expires_at < now()) {
                $res->update(['status' => 'expired']);

                // Restore stock
                $product = Product::find($res->product_id);
                if ($product) {
                    $product->stock = $product->stock + ($res->quantity ?? 1);
                    $product->save();
                }
            }
        }

        // Re-fetch after updates
        $reservations = Reservation::where('user_id', (string) auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.client_orders', ['reservations' => $reservations]);
    }

    /**
     * Seller completes a reservation by entering the 6-digit code.
     * This is the REAL-WORLD workflow: client arrives, gives code, seller validates.
     */
    public function claim(Request $request)
    {
        $request->validate(['confirmation_code' => 'required|string|size:6']);

        $seller = auth()->user();
        $pharmacyId = $seller->pharmacy_id;

        // Find pending reservation matching this code AND this pharmacy
        $reservation = Reservation::where('confirmation_code', $request->confirmation_code)
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', 'pending')
            ->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Invalid code, or no pending reservation found for this pharmacy.');
        }

        // Check expiration
        if ($reservation->expires_at && $reservation->expires_at < now()) {
            $reservation->update(['status' => 'expired']);

            // Restore stock on expiry
            $product = Product::find($reservation->product_id);
            if ($product) {
                $product->stock = $product->stock + ($reservation->quantity ?? 1);
                $product->save();
            }

            return redirect()->back()->with('error', 'This reservation has expired.');
        }

        // Mark as completed
        $reservation->update([
            'status' => 'completed',
            'seller_id' => (string) $seller->_id,
            'completed_at' => now(),
        ]);

        // Generate sale record
        $product = Product::find($reservation->product_id);

        Sale::create([
            'product_id' => $reservation->product_id,
            'product_name' => $reservation->product_name,
            'pharmacy_id' => $reservation->pharmacy_id,
            'pharmacy_name' => $reservation->pharmacy_name,
            'seller_id' => (string) $seller->_id,
            'seller_name' => $seller->prenom . ' ' . $seller->nom,
            'client_id' => $reservation->user_id,
            'client_name' => $reservation->client_name ?? 'Client',
            'quantity' => $reservation->quantity ?? 1,
            'price' => $product ? $product->price : 0,
            'reservation_id' => (string) $reservation->_id,
            'sold_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Reservation completed successfully! Sale recorded.');
    }

    /**
     * Owner views all reservations for their pharmacy.
     */
    public function ownerIndex()
    {
        $user = auth()->user();
        $pharmacyId = $user->pharmacy_id;

        $reservations = $pharmacyId
            ? Reservation::where('pharmacy_id', $pharmacyId)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view('dashboard.owner.owner_reservation', compact('reservations'));
    }

    /**
     * Seller views all reservations for their pharmacy.
     */
    public function sellerIndex()
    {
        $user = auth()->user();
        $pharmacyId = $user->pharmacy_id;

        $reservations = $pharmacyId
            ? Reservation::where('pharmacy_id', $pharmacyId)
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view('dashboard.seller.seller_reservations', compact('reservations'));
    }
}