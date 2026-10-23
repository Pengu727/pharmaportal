<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    // Owner: list products with filtering + alert counts
    public function ownerIndex(Request $request)
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id ?? null;

        if (!$pharmacyId) {
            $products = collect();
            $alerts = $this->emptyAlerts();
            return view('dashboard.owner.owner_inventory', compact('products', 'alerts'));
        }

        $allProducts = Product::where('pharmacy_id', $pharmacyId)->get();

        // Compute alert counts
        $alerts = $this->computeAlerts($allProducts);

        // Apply filter
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');

        $products = $this->applyFilter($allProducts, $filter, $search);

        return view('dashboard.owner.owner_inventory', compact('products', 'alerts', 'filter', 'search'));
    }

    // Owner: store new product
    public function ownerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'dosage' => 'nullable|string|max:100',
            'laboratory' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_threshold' => 'nullable|integer|min:0',
            'expiration_date' => 'required|date|after:today',
            'visibility_status' => 'nullable|in:public,private',
        ]);

        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id ?? null;
        if (!$pharmacyId) {
            return back()->withErrors(['error' => 'Unable to determine your pharmacy context.']);
        }

        Product::create([
            'pharmacy_id' => $pharmacyId,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category' => $request->category ?? 'General',
            'dosage' => $request->dosage,
            'laboratory' => $request->laboratory,
            'price' => (float) $request->price,
            'stock' => (int) $request->stock,
            'min_threshold' => (int) ($request->min_threshold ?? 10),
            'expiration_date' => $request->expiration_date,
            'visibility_status' => $request->visibility_status ?? 'public',
            'is_public' => ($request->visibility_status ?? 'public') === 'public',
        ]);

        return redirect()->route('owner.inventory')->with('status', 'Product added successfully.');
    }

    // Owner: edit form
    public function ownerEdit($id)
    {
        $product = Product::findOrFail($id);
        $this->authorizePharmacyAccess($product);
        return view('dashboard.owner.owner_inventory_edit', compact('product'));
    }

    // Owner: update product
    public function ownerUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorizePharmacyAccess($product);

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'dosage' => 'nullable|string|max:100',
            'laboratory' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_threshold' => 'nullable|integer|min:0',
            'expiration_date' => 'required|date',
            'visibility_status' => 'nullable|in:public,private',
        ]);

        $visibility = $request->visibility_status ?? $product->visibility_status ?? 'public';

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category' => $request->category ?? $product->category,
            'dosage' => $request->dosage,
            'laboratory' => $request->laboratory,
            'price' => (float) $request->price,
            'stock' => (int) $request->stock,
            'min_threshold' => (int) ($request->min_threshold ?? $product->min_threshold ?? 10),
            'expiration_date' => $request->expiration_date,
            'visibility_status' => $visibility,
            'is_public' => $visibility === 'public',
        ]);

        return redirect()->route('owner.inventory')->with('status', 'Product updated.');
    }

    // Owner: delete product
    public function ownerDestroy($id)
    {
        $product = Product::findOrFail($id);
        $this->authorizePharmacyAccess($product);

        $product->delete();

        return redirect()->route('owner.inventory')->with('status', 'Product deleted.');
    }

    // Seller: view available products with filtering + alert counts (READ-ONLY)
    public function sellerIndex(Request $request)
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id ?? null;

        if (!$pharmacyId) {
            $products = collect();
            $alerts = $this->emptyAlerts();
            return view('dashboard.seller.seller_inventory', compact('products', 'alerts'));
        }

        $allProducts = Product::where('pharmacy_id', $pharmacyId)->get();

        // Compute alert counts
        $alerts = $this->computeAlerts($allProducts);

        // Apply filter
        $filter = $request->query('filter', 'all');
        $search = $request->query('search', '');

        $products = $this->applyFilter($allProducts, $filter, $search);

        return view('dashboard.seller.seller_inventory', compact('products', 'alerts', 'filter', 'search'));
    }

    // Seller: decrement stock (walk-in sale without reservation)
    public function decrement(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorizePharmacyAccess($product);

        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);

        $quantity = (int) ($request->quantity ?? 1);

        if ($product->stock < $quantity) {
            return back()->withErrors(['error' => "Only {$product->stock} items available."]);
        }

        $product->stock = max(0, $product->stock - $quantity);
        $product->save();

        $seller = Auth::user();
        $pharmacy = \App\Models\Pharmacy::find($product->pharmacy_id);

        \App\Models\Sale::create([
            'product_id' => (string) $product->_id,
            'product_name' => $product->name,
            'pharmacy_id' => (string) $product->pharmacy_id,
            'pharmacy_name' => $pharmacy ? $pharmacy->nom_pharmacie : 'Unknown',
            'seller_id' => (string) $seller->_id,
            'seller_name' => $seller->prenom . ' ' . $seller->nom,
            'client_id' => null,
            'client_name' => null,
            'quantity' => $quantity,
            'price' => $product->price,
            'reservation_id' => null,
            'sold_at' => now(),
        ]);

        return back()->with('status', "Stock decremented by {$quantity} (sale recorded).");
    }

    /**
     * Compute alert counts from a product collection.
     */
    private function computeAlerts($products): array
    {
        $now = Carbon::today();

        return [
            'total' => $products->count(),
            'public' => $products->filter(fn($p) => ($p->visibility_status ?? 'public') === 'public')->count(),
            'private' => $products->filter(fn($p) => ($p->visibility_status ?? 'public') === 'private')->count(),
            'low_stock' => $products->filter(fn($p) => $p->status === 'LOW_STOCK')->count(),
            'expiring_soon' => $products->filter(fn($p) => $p->status === 'EXPIRING_SOON')->count(),
            'expired' => $products->filter(fn($p) => $p->status === 'EXPIRED')->count(),
        ];
    }

    private function emptyAlerts(): array
    {
        return ['total' => 0, 'public' => 0, 'private' => 0, 'low_stock' => 0, 'expiring_soon' => 0, 'expired' => 0];
    }

    /**
     * Apply filter + search to a product collection.
     */
    private function applyFilter($products, string $filter, string $search)
    {
        // Search first
        if (!empty($search)) {
            $searchLower = mb_strtolower($search);
            $products = $products->filter(function ($p) use ($searchLower) {
                return str_contains(mb_strtolower($p->name ?? ''), $searchLower)
                    || str_contains(mb_strtolower($p->description ?? ''), $searchLower);
            });
        }

        // Then filter
        return match ($filter) {
            'public' => $products->filter(fn($p) => ($p->visibility_status ?? 'public') === 'public'),
            'private' => $products->filter(fn($p) => ($p->visibility_status ?? 'public') === 'private'),
            'low_stock' => $products->filter(fn($p) => $p->status === 'LOW_STOCK'),
            'expiring_soon' => $products->filter(fn($p) => $p->status === 'EXPIRING_SOON'),
            'expired' => $products->filter(fn($p) => $p->status === 'EXPIRED'),
            'available' => $products->filter(fn($p) => $p->stock > 0 && $p->status !== 'EXPIRED'),
            default => $products,
        };
    }

    private function authorizePharmacyAccess(Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $userPharmacy = $user->pharmacy_id ?? null;
        if ($userPharmacy === null || (string) $product->pharmacy_id !== (string) $userPharmacy) {
            abort(403, 'Unauthorized');
        }
    }
}
