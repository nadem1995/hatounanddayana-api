<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\Ui\ProductResource;

use App\Http\Resources\Ui\CategoryResource;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::select('id', 'name', 'price', 'slug', 'description')
            ->withVariants();

        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 📂 Category filter
        if ($request->has('categories')) {

            $categories = is_array($request->categories)
                ? $request->categories
                : [$request->categories];

            // ✅ remove empty/null values
            $categories = array_filter($categories);

            if (!empty($categories)) {
                $query->whereHas('categories', function ($q) use ($categories) {
                    $q->whereIn('slug', $categories);
                });
            }
        }

        // ⭐ Best seller filter
        if ($request->best_seller) {
            $query->where('is_best_seller', true);
        }

        // 💰 Price filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
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

        // 📄 Pagination
        $products = $query->paginate(9);

        $categories = Category::where('status', true)
            ->has('products')
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json([
            'products' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'categories' => CategoryResource::collection($categories),
        ]);
    }



    public function show($id)
    {
        $product = Product::with(['variants.images'])
            ->findOrFail($id);

        return new ProductResource($product);
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
