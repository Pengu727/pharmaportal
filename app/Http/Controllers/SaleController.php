<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Owner views sales history for their pharmacy.
     */
    public function ownerIndex()
    {
        $user = Auth::user();
        $pharmacyId = $user->pharmacy_id;

        $sales = $pharmacyId
            ? Sale::where('pharmacy_id', $pharmacyId)
                ->orderBy('sold_at', 'desc')
                ->get()
            : collect();

        return view('dashboard.owner.owner_sales', compact('sales'));
    }

    /**
     * Seller views their own sales history.
     */
    public function sellerIndex()
    {
        $user = Auth::user();

        $sales = \App\Models\Sale::where('seller_id', (string) $user->_id)
            ->orderBy('sold_at', 'desc')
            ->get();

        return view('dashboard.seller.seller_sales', compact('sales'));
    }
}
