<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display unique categories or filter products inside a selected category.
     * Only public products are shown to clients.
     */
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');

        // Only public products visible to clients
        $allProducts = Product::where(function ($q) {
            $q->where('visibility_status', 'public')
              ->orWhere(function ($q2) {
                  $q2->whereNull('visibility_status')->where('is_public', true);
              });
        })->get();

        // Extract unique categories from the root 'category' field
        $categories = $allProducts->map(function ($prod) {
            $cat = $prod->category;

            // Fallback: check metadata.category if root category is empty
            if (empty($cat) && isset($prod->metadata['category'])) {
                $cat = $prod->metadata['category'];
            }

            if (empty($cat)) {
                $cat = 'General';
            }

            if (is_array($cat)) {
                return isset($cat['name']) ? (string) $cat['name'] : (string) reset($cat);
            }

            return (string) $cat;
        })->unique()->filter()->values();

        $products = collect();
        if (!empty($selectedCategory)) {
            $products = Product::where(function ($q) {
                    $q->where('visibility_status', 'public')
                      ->orWhere(function ($q2) {
                          $q2->whereNull('visibility_status')->where('is_public', true);
                      });
                })
                ->where(function ($q) use ($selectedCategory) {
                    $q->where('category', $selectedCategory)
                      ->orWhere('metadata.category', $selectedCategory);
                })
                ->paginate(9);
        }

        return view('client.client_categories', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    /**
     * Show a single product with its pharmacy information.
     * GUARD: Private products return 404.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Visibility guard — private products not accessible to clients
        $visibility = $product->visibility_status ?? ($product->is_public ? 'public' : 'private');
        if ($visibility !== 'public') {
            abort(404, 'Product not found.');
        }

        // Find the pharmacy using the product's pharmacy_id as the Pharmacy _id
        $pharmacy = Pharmacy::find($product->pharmacy_id);

        return view('client.client_product_view', [
            'product' => $product,
            'pharmacy' => $pharmacy,
        ]);
    }
}