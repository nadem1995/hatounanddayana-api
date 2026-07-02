<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreProductRequest;
use App\Http\Requests\Admin\Products\UpdateProductRequest;
use App\Http\Resources\Admin\Categories\CategoryIndexResource;
use App\Http\Resources\Admin\Products\ProductIndexResource;
use App\Http\Resources\Admin\Products\ProductShowResource;
use App\Http\Resources\Admin\Products\ProductCreateDataResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        $products = Product::query()
            ->search($request->search)
            ->withCategories($request->categories)
            ->latest()
            ->paginate(10);

        return ProductIndexResource::collection($products)
            ->additional([
                'meta' => [
                    'categories' => CategoryIndexResource::collection($categories),
                ],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {

            $product = Product::create([
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'status' => $data['status'],
                'description_ar' => $data['description_ar'] ?? null,
                'description_en' => $data['description_en'] ?? null,
                'price' => $data['price'],
            ]);

            if (!empty($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            foreach ($data['variants'] as $variantData) {

                $variant = $product->variants()->create([
                    'color_name_ar' => $variantData['color_name_ar'],
                    'color_name_en' => $variantData['color_name_en'],
                    'color_code' => $variantData['color_code'] ?? null,
                ]);

                if (!empty($variantData['images'])) {
                    foreach ($variantData['images'] as $image) {
                        $path = $image->store('products/variants', 'public');

                        $variant->images()->create([
                            'image' => $path,
                        ]);
                    }
                }
            }

            return response()->json([
                'message' => __('products.created'),
            ], 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with('variants.images')
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->firstOrFail();

        return new ProductShowResource($product);
    }


    public function edit(string $slug)
    {

        $product = Product::with('variants.images')
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->firstOrFail();
        return [
            'product' => new ProductShowResource($product),
            'meta' => new ProductCreateDataResource([
                'categories' => Category::where('status', true)->get(),
            ])
        ];
    }


    /**
     * Update the specified resource in storage.
     */


    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        return DB::transaction(function () use ($product, $data) {

            // Update product
            $product->update([
                'name_ar' => $data['name_ar'],
                'name_en' => $data['name_en'],
                'status' => $data['status'],
                'description_ar' => $data['description_ar'] ?? null,
                'description_en' => $data['description_en'] ?? null,
                'price' => $data['price'],
            ]);

            // Sync categories
            if (!empty($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            $existingVariantIds = $product->variants()->pluck('id')->toArray();
            $receivedVariantIds = [];

            foreach ($data['variants'] as $variantData) {

                // Update existing variant or create a new one
                if (!empty($variantData['id'])) {

                    $variant = $product->variants()->find($variantData['id']);

                    if ($variant) {
                        $variant->update([
                            'color_name_ar' => $variantData['color_name_ar'],
                            'color_name_en' => $variantData['color_name_en'],
                            'color_code' => $variantData['color_code'] ?? null,
                        ]);
                    } else {
                        $variant = $product->variants()->create([
                            'color_name_ar' => $variantData['color_name_ar'],
                            'color_name_en' => $variantData['color_name_en'],
                            'color_code' => $variantData['color_code'] ?? null,
                        ]);
                    }

                } else {

                    $variant = $product->variants()->create([
                        'color_name_ar' => $variantData['color_name_ar'],
                        'color_name_en' => $variantData['color_name_en'],
                        'color_code' => $variantData['color_code'] ?? null,
                    ]);
                }

                $receivedVariantIds[] = $variant->id;

                // Delete selected images
                if (!empty($variantData['deleted_images'])) {

                    $imagesToDelete = $variant->images()
                        ->whereIn('id', $variantData['deleted_images'])
                        ->get();

                    foreach ($imagesToDelete as $image) {
                        Storage::disk('public')->delete(str_replace(asset('storage/') . '/', '', $image->getRawOriginal('image')));
                        $image->delete();
                    }
                }

                // Upload new images
                if (!empty($variantData['new_images'])) {
                    foreach ($variantData['new_images'] as $image) {

                        $path = $image->store('products/variants', 'public');

                        $variant->images()->create([
                            'image' => $path,
                        ]);
                    }
                }
            }

            // Delete removed variants
            $variantsToDelete = array_diff($existingVariantIds, $receivedVariantIds);

            if (!empty($variantsToDelete)) {

                $variants = $product->variants()
                    ->whereIn('id', $variantsToDelete)
                    ->with('images')
                    ->get();

                foreach ($variants as $variant) {

                    foreach ($variant->images as $image) {
                        Storage::disk('public')->delete($image->getRawOriginal('image'));
                    }

                    $variant->images()->delete();
                    $variant->delete();
                }
            }

            return response()->json([
                'message' => __('products.updated'),
            ]);
        });
    }


    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'message' => __('products.deleted'),
        ], 200);
    }

    public function status(string $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'status' => !$product->status,
        ]);


        return response()->json([
            'message' => $product->status ? __('products.status_active') : __('products.status_inactive')
        ]);
    }

    public function createData()
    {
        return new ProductCreateDataResource([
            'categories' => Category::where('status', true)->get(),
        ]);
    }


    public function destroyImageVariant(string $id)
    {
        $productVariantImage = ProductVariantImage::findOrFail($id);
        $productVariantImage->delete();

        return response()->json([
            'message' => __('messages.deleted_successfully'),
        ], 200);
    }
}
