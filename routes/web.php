<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SellerManagementController;
use App\Http\Controllers\SaleController;

// ============== HOME / INDEX ==============
Route::get('/', function () {
    return redirect('/guest-products');
});
Route::get('/guest-products', [App\Http\Controllers\GuestController::class, 'browse'])->name('guest.products');
Route::get('/products/suggest', [App\Http\Controllers\GuestController::class, 'suggest'])->name('products.suggest');

// ============== PUBLIC CLIENT & GUEST ROUTES ==============
Route::get('/client-login', function () {
    return view('client.client_login');
})->name('client.login');

// Named 'login' so Laravel's auth middleware knows where to redirect unauthenticated users
Route::get('/login', function () {
    return redirect()->route('client.login');
})->name('login');

Route::post('/client-login', [AuthController::class, 'login']);

Route::get('/client-register', function () {
    return view('client.client_register');
})->name('client.register');
Route::post('/client-register', [AuthController::class, 'register']);

// Verification routes
Route::get('/client-verify', function () {
    return view('client.client_verify');
})->name('client.verify');
Route::post('/client-verify', [AuthController::class, 'verifyOtp'])->name('client.verify.submit');
Route::post('/client-verify/resend', [AuthController::class, 'resendOtp'])->name('client.verify.resend');

// ============== AUTHENTICATED CLIENT ROUTES ==============
Route::middleware('auth')->group(function () {
    Route::get('/client-orders', [ReservationController::class, 'index'])->name('client.orders');

    Route::get('/client-product/{id}', [CategoryController::class, 'show'])->name('client.product_view');

    Route::get('/client-account-settings', function () {
        return view('client.client_account_settings');
    })->name('client.account_settings');

    Route::get('/client-dashboard', [AuthController::class, 'dashboard'])->name('client.dashboard');

    Route::get('/client-categories', [CategoryController::class, 'index'])->name('client.categories');

    // Near Me
    Route::get('/client-nearme', function (Request $request) {
        $lat = $request->query('lat');
        $lng = $request->query('lng');
        $search = $request->query('search', '');

        $pharmacies = collect();

        if ($lat && $lng) {
            $userLat = (float) $lat;
            $userLng = (float) $lng;

            // Get all pharmacies with coordinates
            $allPharmacies = Pharmacy::all()->filter(function ($p) {
                return isset($p->location['coordinates'])
                    && is_array($p->location['coordinates'])
                    && count($p->location['coordinates']) >= 2
                    && $p->location['coordinates'][0] != 0;
            });

            // Calculate distance for each and sort
            $pharmacies = $allPharmacies->map(function ($p) use ($userLat, $userLng, $search) {
                $pLng = $p->location['coordinates'][0];
                $pLat = $p->location['coordinates'][1];

                // Haversine formula for distance in km
                $earthRadius = 6371;
                $dLat = deg2rad($pLat - $userLat);
                $dLng = deg2rad($pLng - $userLng);
                $a = sin($dLat / 2) * sin($dLat / 2) +
                     cos(deg2rad($userLat)) * cos(deg2rad($pLat)) *
                     sin($dLng / 2) * sin($dLng / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distance = $earthRadius * $c;

                $p->distance = round($distance, 2);

                // Check if pharmacy has matching PUBLIC products
                $productQuery = Product::where('pharmacy_id', (string) $p->_id)
                    ->where(function ($vq) {
                        $vq->where('visibility_status', 'public')
                           ->orWhere(function ($vq2) {
                               $vq2->whereNull('visibility_status')->where('is_public', true);
                           });
                    });

                if (!empty($search)) {
                    $productQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%")
                          ->orWhere('category', 'like', "%{$search}%")
                          ->orWhere('laboratory', 'like', "%{$search}%");
                    });
                }

                $p->matching_products = $productQuery->get();
                $p->product_count = $p->matching_products->count();

                return $p;
            })->filter(function ($p) {
                // Only show pharmacies that have matching products (or all if no search)
                return $p->product_count > 0;
            })->sortBy('distance')->values();
        }

        return view('client.client_nearme', [
            'pharmacies' => $pharmacies,
            'lat' => $lat,
            'lng' => $lng,
            'search' => $search,
        ]);
    })->name('client.nearme');

    // Reservation actions
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
});

// ============== OWNER ROUTES ==============
Route::get('/owner-login', function () {
    return view('dashboard.owner.owner_login');
})->name('owner.login');

Route::middleware('auth:web', 'role:owner')->group(function () {
    Route::get('/owner-dashboard', [InventoryController::class, 'ownerIndex'])->name('owner.dashboard');

    Route::get('/owner/inventory', [InventoryController::class, 'ownerIndex'])->name('owner.inventory');
    Route::post('/owner/inventory', [InventoryController::class, 'ownerStore'])->name('owner.inventory.store');
    Route::get('/owner/inventory/{id}/edit', [InventoryController::class, 'ownerEdit'])->name('owner.inventory.edit');
    Route::put('/owner/inventory/{id}/update', [InventoryController::class, 'ownerUpdate'])->name('owner.inventory.update');
    Route::delete('/owner/inventory/{id}', [InventoryController::class, 'ownerDestroy'])->name('owner.inventory.destroy');

    Route::get('/owner/reservations', [ReservationController::class, 'ownerIndex'])->name('owner.reservations');

    // Seller management
    Route::get('/owner/sellers', [SellerManagementController::class, 'index'])->name('owner.sellers');
    Route::post('/owner/sellers', [SellerManagementController::class, 'store'])->name('owner.sellers.store');
    Route::delete('/owner/sellers/{id}', [SellerManagementController::class, 'destroy'])->name('owner.sellers.destroy');

    // Sales history
    Route::get('/owner/sales', [SaleController::class, 'ownerIndex'])->name('owner.sales');
});

// ============== SELLER ROUTES ==============
Route::get('/seller-login', function () {
    return view('dashboard.seller.seller_login');
})->name('seller.login');

Route::middleware('auth:web', 'role:seller')->group(function () {
    Route::get('/seller-dashboard', [InventoryController::class, 'sellerIndex'])->name('seller.dashboard');

    Route::get('/seller/inventory', [InventoryController::class, 'sellerIndex'])->name('seller.inventory');
    Route::post('/seller/inventory/{id}/decrement', [InventoryController::class, 'decrement'])->name('seller.inventory.decrement');

    // Seller reservation management
    Route::get('/seller/reservations', [ReservationController::class, 'sellerIndex'])->name('seller.reservations');
    Route::post('/seller/reservations/claim', [ReservationController::class, 'claim'])->name('seller.reservations.claim');

    // Seller sales history
    Route::get('/seller/sales', [SaleController::class, 'sellerIndex'])->name('seller.sales');
});

// ============== ADMIN ROUTES ==============
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/pharmacy/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/pharmacy/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/pharmacy/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/pharmacy/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/pharmacy/{id}/update', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/pharmacy/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    });
});

// ============== LOGOUT ==============
Route::post('/logout', function () {
    // Logout from all guards
    Auth::guard('admin')->logout();
    Auth::guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/client-login');
})->name('logout');