<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Http\Requests\Admin\Cms\StoreTestimonialRequest;
use App\Http\Resources\Admin\Cms\TestimonialIndexResource;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TestimonialIndexResource::collection(
            Testimonial::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
         Testimonial::create([
            'name' => $request->name,
            'message' => $request->message,
            'source' => $request->source,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => __('testimonials.created'),
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return response()->json([
            'data' => $testimonial,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->update([
            'name' => $request->name,
            'message' => $request->message,
            'source' => $request->source,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => __('testimonials.updated'),
            'data' => $testimonial
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'message' => __('testimonials.deleted'),
        ], 200);
    }
}
