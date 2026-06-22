<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display unique categories or filter products inside a selected category.
     */
    public function index(Request $request)
{
    $selectedCategory = $request->query('category');
    
    $allProducts = Product::all();
    
    // Explicitly parse and normalize unique categories to raw strings
    $categories = $allProducts->map(function ($prod) {
        $cat = isset($prod->metadata['category']) ? $prod->metadata['category'] : 'General';
        
        // If the category field itself is nested as an array, extract the first value or string representation
        if (is_array($cat)) {
            return isset($cat['name']) ? (string)$cat['name'] : (string)reset($cat);
        }
        
        return (string)$cat;
    })->unique()->filter()->values(); // Added values() to reset collection keys cleanly

    $products = collect();
    if (!empty($selectedCategory)) {
        $products = Product::where('metadata.category', $selectedCategory)
            ->orWhere('category', $selectedCategory) // Also fall back to root category field if present
            ->paginate(9);
    }

    return view('client.client_categories', [
        'categories' => $categories,
        'products' => $products,
        'selectedCategory' => $selectedCategory
    ]);
}
    public function show($id)
    {
        // 1. Find the product by its MongoDB ID
        $product = Product::findOrFail($id);

        // 2. Find the associated pharmacy using the relationship key
        // Matches the "pharm_6a35bd9..." style string format in your documents
        $pharmacy = Pharmacy::where('pharmacy_id', $product->pharmacy_id)->first();

        return view('client.client_product_view', [
            'product' => $product,
            'pharmacy' => $pharmacy
        ]);
    }
}