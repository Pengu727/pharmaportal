<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// =====================
// PUBLIC API ROUTES
// =====================

// Registration
Route::post('/register', [AuthController::class, 'register']);

// Login (returns token)
Route::post('/login', [AuthController::class, 'login']);

// =====================
// PHARMACIES API
// =====================
Route::get('/pharmacies', function () {
    $pharmacies = Pharmacy::all();
    return response()->json([
        'status' => 'success',
        'data' => $pharmacies
    ]);
});

Route::get('/pharmacies/{id}', function ($id) {
    $pharmacy = Pharmacy::find($id);

    if (!$pharmacy) {
        return response()->json([
            'status' => 'error',
            'message' => 'Pharmacy not found',
        ], 404);
    }

    // Get the owner user
    $owner = User::where('pharmacy_id', (string) $pharmacy->_id)
        ->where('role', 'owner')
        ->first();

    return response()->json([
        'status' => 'success',
        'data' => [
            'pharmacy' => $pharmacy,
            'owner' => $owner ? [
                'nom' => $owner->nom,
                'prenom' => $owner->prenom,
                'email' => $owner->email,
            ] : null,
        ]
    ]);
});

// =====================
// PRODUCTS API
// =====================
Route::get('/products', function (Request $request) {
    $query = Product::where('is_public', true);

    if ($request->has('search')) {
        $search = $request->query('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    if ($request->has('pharmacy_id')) {
        $query->where('pharmacy_id', $request->query('pharmacy_id'));
    }

    $products = $query->paginate(20);

    return response()->json([
        'status' => 'success',
        'data' => $products
    ]);
});