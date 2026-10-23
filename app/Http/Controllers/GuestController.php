<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Guest browse products using traditional server-side rendering.
     * Only public products are shown.
     */
    public function browse(Request $request)
    {
        $search = $request->query('search', '');
        $products = \App\Services\ProductSearchService::search($search, 12);

        return view('guest.guest_products', compact('products'));
    }

    public function suggest(Request $request)
    {
        $search = $request->query('search', '');
        $suggestions = \App\Services\ProductSearchService::suggest($search);

        return response()->json($suggestions);
    }
}