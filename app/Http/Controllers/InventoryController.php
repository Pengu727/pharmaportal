<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    // Owner: list products
    public function ownerIndex(Request $request)
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id ?? null;
        $products = $pharmacyId ? Product::where('pharmacy_id', $pharmacyId)->get() : collect();

        return view('dashboard.owner.owner_inventory', compact('products'));
    }

    // Owner: store new product
    public function ownerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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
            'price' => (float)$request->price,
            'stock' => (int)$request->stock,
        ]);

        return redirect()->route('owner.inventory')->with('status', 'Product added.');
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => (float)$request->price,
            'stock' => (int)$request->stock,
        ]);

        return redirect()->route('owner.inventory')->with('status', 'Product updated.');
    }

    // Seller: view available products
    public function sellerIndex()
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id ?? null;
        $products = $pharmacyId ? Product::where('pharmacy_id', $pharmacyId)->get() : collect();

        return view('dashboard.seller.seller_inventory', compact('products'));
    }

    // Seller: decrement stock (simple sale)
    public function decrement(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorizePharmacyAccess($product);

        if ($product->stock <= 0) {
            return back()->withErrors(['error' => 'Product out of stock.']);
        }

        // Basic decrement; replace with atomic DB update when adding concurrency control
        $product->stock = max(0, $product->stock - 1);
        $product->save();

        return back()->with('status', 'Stock decremented (sale recorded).');
    }

    private function authorizePharmacyAccess(Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $userPharmacy = $user->pharmacy_id ?? null;
        if ($userPharmacy === null || (string)$product->pharmacy_id !== (string)$userPharmacy) {
            abort(403, 'Unauthorized');
        }
    }
}
