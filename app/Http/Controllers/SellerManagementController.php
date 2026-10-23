<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SellerManagementController extends Controller
{
    /**
     * Display sellers for the owner's pharmacy.
     */
    public function index()
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id;

        $sellers = $pharmacyId
            ? User::where('pharmacy_id', $pharmacyId)->where('role', 'seller')->get()
            : collect();

        return view('dashboard.owner.owner_sellers', compact('sellers'));
    }

    /**
     * Create a new seller account linked to the owner's pharmacy.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id;

        if (!$pharmacyId) {
            return back()->withErrors(['error' => 'No pharmacy assigned to your account.']);
        }

        User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'seller',
            'pharmacy_id' => $pharmacyId,
            'is_verified' => true,
        ]);

        return redirect()->route('owner.sellers')->with('status', 'Seller account created successfully.');
    }

    /**
     * Delete a seller account.
     */
    public function destroy($id)
    {
        $seller = User::findOrFail($id);
        $user = Auth::user();

        // Verify the seller belongs to the owner's pharmacy
        if ($seller->role !== 'seller' || (string) $seller->pharmacy_id !== (string) $user->pharmacy_id) {
            abort(403, 'Unauthorized');
        }

        $seller->delete();

        return redirect()->route('owner.sellers')->with('status', 'Seller account deleted.');
    }
}
