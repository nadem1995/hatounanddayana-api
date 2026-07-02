<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ui\ProductDetailResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\Ui\ProductResource;

use App\Http\Resources\Ui\CategoryResource;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        $query = Product::withVariants();

        // 🔍 Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%");
            });
        }

        // 📂 Category filter
        if ($request->filled('categories')) {

            $categories = is_array($request->categories)
                ? $request->categories
                : [$request->categories];

            $categories = array_filter($categories);

            if (!empty($categories)) {
                $query->whereHas('categories', function ($q) use ($categories) {
                    $q->whereIn('slug', $categories);
                });
            }
        }

        // 💰 Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🔃 Sorting
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'latest') {
            $query->latest();
        } elseif ($request->sort === 'oldest') {
            $query->oldest();
        }

        // 📄 Pagination (IMPORTANT CHANGE)
        $products = $query->paginate(9);

        // 📂 Categories list (for filters UI)
        $categories = Category::where('status', true)
            ->get();

        return response()->json([
            'products' => ProductResource::collection($products),

            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],

            'categories' =>CategoryResource::collection($categories)
        ]);
    }

    public function show($slug)
    {
        $product = Product::with(['variants.images'])
           -> where('slug', $slug)->firstOrFail();

        return new ProductDetailResource($product);
    }


    public function getFavorite(Request $request)
    {
        $ids = $request->input('favorites', []);

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'products' => []
            ]);
        }

        $products = Product::withVariants()
            ->whereIn('id', $ids)
            ->get();

        return response()->json([
            'products' => ProductResource::collection($products)
        ]);
    }
}
