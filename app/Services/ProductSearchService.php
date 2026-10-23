<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Pharmacy;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ProductSearchService
{
    /**
     * Search and rank products.
     * Returns a standard paginator.
     */
    public static function search($search, $perPage = 12)
    {
        $query = Product::where(function ($q) {
            $q->where('visibility_status', 'public')
              ->orWhere(function ($q2) {
                  $q2->whereNull('visibility_status')->where('is_public', true);
              });
        });

        if (empty($search)) {
            return $query->paginate($perPage);
        }

        // Find matching pharmacies
        $pharmacies = Pharmacy::where('nom_pharmacie', 'like', "%{$search}%")->get(['_id', 'nom_pharmacie']);
        $pharmacyIds = $pharmacies->pluck('_id');

        // Apply search filters
        $query->where(function($q) use ($search, $pharmacyIds) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('laboratory', 'like', "%{$search}%");

            if ($pharmacyIds->isNotEmpty()) {
                $q->orWhereIn('pharmacy_id', $pharmacyIds);
            }
        });

        // Fetch all matching products to score them
        $products = $query->with('pharmacy')->get();
        $searchLower = strtolower($search);

        $products = $products->map(function($product) use ($searchLower, $pharmacyIds) {
            $score = 0;
            $nameLower = strtolower($product->name ?? '');

            if ($nameLower === $searchLower) {
                $score = 100;
            } elseif (str_contains($nameLower, $searchLower)) {
                $score = 80;
            } elseif ($pharmacyIds->contains($product->pharmacy_id)) {
                $score = 60;
            } elseif (str_contains(strtolower($product->category ?? ''), $searchLower)) {
                $score = 40;
            } elseif (str_contains(strtolower($product->laboratory ?? ''), $searchLower)) {
                $score = 30;
            } elseif (str_contains(strtolower($product->description ?? ''), $searchLower)) {
                $score = 10;
            } else {
                $score = 1;
            }

            $product->search_score = $score;
            return $product;
        });

        $products = $products->sortByDesc('search_score')->values();

        $page = Paginator::resolveCurrentPage() ?: 1;
        return new LengthAwarePaginator(
            $products->forPage($page, $perPage),
            $products->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );
    }

    /**
     * Provide a ranked list of suggestions for the autocomplete dropdown.
     */
    public static function suggest($search)
    {
        if (empty($search)) {
            return [];
        }

        // Find matching pharmacies
        $pharmacies = Pharmacy::where('nom_pharmacie', 'like', "%{$search}%")->get(['_id', 'nom_pharmacie']);
        $pharmacyIds = $pharmacies->pluck('_id');

        $query = Product::where(function ($q) {
            $q->where('visibility_status', 'public')
              ->orWhere(function ($q2) {
                  $q2->whereNull('visibility_status')->where('is_public', true);
              });
        })->where(function($q) use ($search, $pharmacyIds) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('laboratory', 'like', "%{$search}%");

            if ($pharmacyIds->isNotEmpty()) {
                $q->orWhereIn('pharmacy_id', $pharmacyIds);
            }
        });

        $products = $query->with('pharmacy')->get();
        $searchLower = strtolower($search);

        $suggestions = collect();

        foreach ($products as $product) {
            $nameLower = strtolower($product->name ?? '');

            if ($nameLower === $searchLower || str_contains($nameLower, $searchLower)) {
                $suggestions->push([
                    'text' => $product->name,
                    'type' => 'Product',
                    'score' => $nameLower === $searchLower ? 100 : 80
                ]);
            } elseif ($pharmacyIds->contains($product->pharmacy_id)) {
                $pharmacyName = $product->pharmacy->nom_pharmacie ?? 'Pharmacy';
                $suggestions->push([
                    'text' => $pharmacyName,
                    'type' => 'Pharmacy',
                    'score' => 60
                ]);
            } elseif (str_contains(strtolower($product->category ?? ''), $searchLower)) {
                $suggestions->push([
                    'text' => $product->category,
                    'type' => 'Category',
                    'score' => 40
                ]);
            } elseif (str_contains(strtolower($product->laboratory ?? ''), $searchLower)) {
                $suggestions->push([
                    'text' => $product->laboratory,
                    'type' => 'Laboratory',
                    'score' => 30
                ]);
            } elseif (str_contains(strtolower($product->description ?? ''), $searchLower)) {
                // If the description matches, suggest the product name as it's cleaner for autocomplete
                $suggestions->push([
                    'text' => $product->name,
                    'type' => 'Description',
                    'score' => 10
                ]);
            }
        }

        // Unique by text and type, sort by score desc, take 5
        return $suggestions->unique(function ($item) {
            return strtolower($item['text']) . '-' . $item['type'];
        })->sortByDesc('score')->take(5)->values()->toArray();
    }
}
