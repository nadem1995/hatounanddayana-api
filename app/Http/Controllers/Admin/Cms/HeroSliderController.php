<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => HeroSlider::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('image')->store('hero-slider', 'public');

        $slider = HeroSlider::create([
            'image' => $path,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'message' => __('hero_slider.created'),
            'data' => $slider
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $slider = HeroSlider::findOrFail($id);

        return response()->json([
            'data' => $slider
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $slider = HeroSlider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
            'is_active' => 'boolean'
        ]);

        // Update image if exists
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image);

            $slider->image = $request->file('image')->store('hero-slider', 'public');
        }

        $slider->update([
            'is_active' => $request->is_active ?? $slider->is_active,
        ]);

        return response()->json([
            'message' => __('hero_slider.updated'),
            'data' => $slider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = HeroSlider::findOrFail($id);

        Storage::disk('public')->delete($slider->image);
        $slider->delete();

        return response()->json([
            'message' => __('hero_slider.deleted'),
        ]);
    }


    public function status(string $id)
    {
        $heroSlider = HeroSlider::findOrFail($id);
        $heroSlider->update([
            'status' => ! $heroSlider->status,
        ]);
        return response()->json([
            'message' => $heroSlider->status ? __('hero_slider.status_active') : __('hero_slider.status_inactive')
        ]);
    }
}
