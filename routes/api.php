<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route for Account Provisioning
Route::post('/auth/register', [AuthController::class, 'register']);

// Route for OTP Security Verification
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/auth/login', [AuthController::class, 'login']);

// 1. Test as a Client
Route::get('/test-as-client', function () {
    return response()->json(['message' => 'The middleware let you pass! Welcome Client.']);
})->middleware('role:client');

// 2. Test as a Seller
Route::get('/test-as-seller', function () {
    return response()->json(['message' => 'The middleware let you pass! Welcome Seller.']);
})->middleware('role:seller');

// 3. Test as an Owner
Route::get('/test-as-owner', function () {
    return response()->json(['message' => 'The middleware let you pass! Welcome Owner.']);
})->middleware('role:owner');

// API Route to fetch all existing sellers from MongoDB
Route::get('/owner/sellers', function() {
    $sellers = User::where('role', 'seller')->get(['nom', 'prenom', 'email', 'role']);
    return response()->json(['data' => $sellers]);
});

// API Route to create a new seller under MongoDB
Route::post('/owner/sellers', function(Request $request) {
    $request->validate([
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6'
    ]);

    User::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'seller',
        'is_verified' => true // Staff accounts are pre-verified by the owner
    ]);

    return response()->json(['status' => 'success'], 201);
});

Route::get('/admin/pharmacies', function() {
    try {
        $pharmacies = Pharmacy::all();
        $data = [];

        foreach ($pharmacies as $pharmacy) {
            // Safely look for the owner tied to this pharmacy
            $owner = User::where('role', 'owner')->where('pharmacy_id', $pharmacy->id)->first();
            
            $data[] = [
                'name' => $pharmacy->name,
                'address' => $pharmacy->address,
                'phone' => $pharmacy->phone ?? 'N/A',
                'license_number' => $pharmacy->license_number ?? 'N/A',
                'owner_name' => $owner ? ($owner->prenom . ' ' . $owner->nom) : 'Not Assigned',
                'owner_email' => $owner ? $owner->email : 'N/A'
            ];
        }

        return response()->json(['data' => $data], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

// 2. POST: Deploy pharmacy with license and phone numbers
Route::post('/admin/pharmacies', function(Request $request) {
    $request->validate([
        'pharmacy_name' => 'required|string',
        'pharmacy_address' => 'required|string',
        'phone' => 'required|string',
        'license_number' => 'required|string',
        'owner_nom' => 'required|string',
        'owner_prenom' => 'required|string',
        'owner_email' => 'required|email|unique:users,email',
        'owner_password' => 'required|string|min:6'
    ]);

    // Save to MongoDB pharmacies collection
    $pharmacy = Pharmacy::create([
        'name' => $request->pharmacy_name,
        'address' => $request->pharmacy_address,
        'phone' => $request->phone,
        'license_number' => $request->license_number,
    ]);

    // Create the Owner account linked to the new pharmacy
    User::create([
        'nom' => $request->owner_nom,
        'prenom' => $request->owner_prenom,
        'email' => $request->owner_email,
        'password' => Hash::make($request->owner_password),
        'role' => 'owner',
        'pharmacy_id' => $pharmacy->id,
        'is_verified' => true
    ]);

    return response()->json(['status' => 'success'], 201);
});