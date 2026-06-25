<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroImageController extends Controller
{
    /**
     * Get hero image
     */
    public function show()
    {
        $heroImage = HeroImage::first();
        if (!$heroImage) {
            return response()->json([
                'data' => null,
            ], 404);
        }
        return response()->json([
            'data' => [
                'id' => $heroImage->id,
                'image' => $heroImage->image,
            ]
        ]);
    }

    /**
     * Create hero image
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if (HeroImage::exists()) {
            return response()->json([
                'message' => __('hero_image.already_exists'),
            ], 422);
        }

        $path = $request->file('image')->store('hero-images', 'public');

        $heroImage = HeroImage::create([
            'image' => $path,
        ]);

        return response()->json([
            'message' => __('hero_image.created'),
        ], 201);
    }

    /**
     * Update hero image
     */
    public function update(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $heroImage = HeroImage::first();

        if (!$heroImage) {
            return response()->json([
                'message' => __('hero_image.not_found'),
            ], 404);
        }

        if ($heroImage->image) {
            Storage::disk('public')->delete($heroImage->image);
        }

        $heroImage->image = $request
            ->file('image')
            ->store('hero-images', 'public');

        $heroImage->save();

        return response()->json([
            'message' => __('hero_image.updated'),
            'data' => $heroImage,
        ]);
    }


    public function destroy()
    {
        $heroImage = HeroImage::first();

        if (!$heroImage) {
            return response()->json([
                'message' => __('hero_image.not_found'),
            ], 404);
        }

        Storage::disk('public')->delete($heroImage->image);

        $heroImage->delete();

        return response()->json([
            'message' => __('hero_image.deleted'),
        ]);
    }
}
