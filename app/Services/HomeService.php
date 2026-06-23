<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\HeroImage;
use App\Http\Resources\Ui\ProductResource;
use App\Http\Resources\Ui\CategoryResource;

class HomeService
{
    public function getHomeData()
    {
        $baseQuery = Product::select('id', 'name', 'price', 'slug', 'description')
            ->withVariants();

        return [
            'categories' => $this->categories(),
            'hero_image' => $this->heroImage(),
            /* 'latest_products' => $this->latestProducts($baseQuery),*/
        ];
    }

    private function categories()
    {
        return CategoryResource::collection(
            Category::where('status', true)
                ->select('id', 'name', 'image', 'slug', 'description')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }


    private function heroImage()
    {
        return HeroImage::select('id', 'image')
            ->first();
    }
}
