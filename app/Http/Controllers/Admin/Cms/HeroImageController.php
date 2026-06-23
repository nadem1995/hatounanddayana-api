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
                'success' => false,
                'message' => 'Hero image not found.',
                'data' => null,
            ], 404);
        }
        return response()->json([
            'data' => [
                'id' => $heroImage->id,
                'image' => asset('storage/' . $heroImage->image),
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
                'success' => false,
                'message' => 'Hero image already exists. Use update instead.',
            ], 422);
        }

        $path = $request->file('image')->store('hero-images', 'public');

        $heroImage = HeroImage::create([
            'image' => $path,
        ]);

        return response()->json([
            'message' => 'Hero image created successfully.',
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
                'success' => false,
                'message' => 'Hero image not found.',
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
            'success' => true,
            'message' => 'Hero image updated successfully.',
            'data' => $heroImage,
        ]);
    }


    public function destroy()
    {
        $heroImage = HeroImage::first();

        if (!$heroImage) {
            return response()->json([
                'success' => false,
                'message' => 'Hero image not found.',
            ], 404);
        }

        Storage::disk('public')->delete($heroImage->image);

        $heroImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero image deleted successfully.',
        ]);
    }
}
