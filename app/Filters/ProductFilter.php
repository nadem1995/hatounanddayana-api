<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductFilter
{
    
    
    public static function apply(Request $request, Builder $query): Builder
    {
        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 📂 Category
        if ($request->category_id) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // ⭐ Best seller
        if ($request->best_seller) {
            $query->where('is_best_seller', true);
        }

        // 💰 Price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🔃 Sorting
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        }

        if ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        }

        if ($request->sort === 'latest') {
            $query->latest();
        }

        return $query;
    }
}