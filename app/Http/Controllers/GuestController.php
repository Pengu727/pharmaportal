<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Guest browse products using traditional server-side rendering.
     */
    public function browse(Request $request)
    {
        $search = $request->query('search', '');
        $perPage = 12;

        // Filter: Only show public products
        $query = Product::where('is_public', true);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate($perPage);

        return view('guest.guest_products', compact('products'));
    }

    public function suggest(Request $request)
    {
        $search = $request->query('search', '');

        if (empty($search)) {
            return response()->json([]);
        }

        // Filter: Suggestions also respect the public flag
        $suggestions = Product::where('is_public', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->limit(5)
            ->get(['_id', 'id', 'name']);

        return response()->json($suggestions);
    }
}