<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pharmacy;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CategoryController;

// ============== HOME / INDEX ==============
Route::get('/', function () {
    return redirect('/guest-products');
});
Route::get('/guest-products', [App\Http\Controllers\GuestController::class, 'browse'])->name('guest.products');
Route::get('/products/suggest', [App\Http\Controllers\GuestController::class, 'suggest'])->name('products.suggest');

// ============== PUBLIC CLIENT & GUEST ROUTES ==============
// FIXED: Changed the name to 'login' so Laravel's auth middleware knows exactly where to redirect guests!
Route::get('/client-login', function () {
    return view('client.client_login'); 
})->name('login');
Route::post('/client-login', [AuthController::class, 'login']);

Route::get('/client-register', function () {
    return view('client.client_register'); 
})->name('client.register');
Route::post('/client-register', [AuthController::class, 'register']);

// New Verification routes
Route::get('/client-verify', function () {
    return view('client.client_verify');
})->name('client.verify');
Route::post('/client-verify', [AuthController::class, 'verifyOtp'])->name('client.verify.submit');

// ============== AUTHENTICATED PATIENT ROUTES ==============
Route::middleware('auth')->group(function () {
    Route::get('/client-orders', [ReservationController::class, 'index'])->name('client.orders');
    
    Route::get('/client-product/{id}', [CategoryController::class, 'show'])->name('client.product_view');
    
    Route::get('/client-account-settings', function () { 
        return view('client.client_account_settings'); 
    })->name('client.account_settings');
    
    Route::get('/client-dashboard', [AuthController::class, 'dashboard'])->name('client.dashboard');
    
    // FIXED: Only kept the controller-based route here so it processes your CategoryController index method safely!
    Route::get('/client-categories', [CategoryController::class, 'index'])->name('client.categories');
    
    // Core active patient reservation actions
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
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
    
    Route::get('/owner/reservations', [ReservationController::class, 'ownerIndex'])->name('owner.reservations');
});

// ============== SELLER ROUTES ==============
Route::get('/seller-login', function () {
    return view('dashboard.seller.seller_login');
})->name('seller.login');

Route::middleware('auth:web', 'role:seller')->group(function () {
    Route::get('/seller-dashboard', [InventoryController::class, 'sellerIndex'])->name('seller.dashboard');
    
    Route::get('/seller/inventory', [InventoryController::class, 'sellerIndex'])->name('seller.inventory');
    Route::post('/seller/inventory/{id}/decrement', [InventoryController::class, 'decrement'])->name('seller.inventory.decrement');
    
    Route::post('/seller/reservations/claim', [ReservationController::class, 'claim'])->name('seller.reservations.claim');
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
    });
});

// ============== LOGOUT ==============
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    return redirect('/client-login');
})->name('logout');