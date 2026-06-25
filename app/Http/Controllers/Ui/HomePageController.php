<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ui\CategoryResource;
use App\Models\Category;
use App\Models\HeroImage;

class HomePageController extends Controller
{
    public function index()
    {
        $heroImage = HeroImage::first();

        return response()->json([
            'data' => [
                'categories' => CategoryResource::collection(
                    Category::where('status', 1)->get()
                ),
                'heroImage' => $heroImage ? [
                    'id' => $heroImage->id,
                    'image' => $heroImage->image,
                ] : null,
            ],
        ]);
    }
}
