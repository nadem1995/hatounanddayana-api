<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\StoreTestimonialRequest;
use App\Http\Requests\Admin\Cms\UpdateTestimonialRequest;
use App\Http\Resources\Admin\Cms\TestimonialResource;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        return TestimonialResource::collection(
            Testimonial::all()
        );
    }

    public function store(StoreTestimonialRequest $request)
    {
        Testimonial::create([
            'name' => $request->name,
            'message_en' => $request->message_en,
            'message_ar' => $request->message_ar,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => __('testimonials.created'),
        ], 201);
    }

    public function show(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return new TestimonialResource($testimonial);
    }

    public function update(UpdateTestimonialRequest $request, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->update([
            'name' => $request->name,
            'message_en' => $request->message_en,
            'message_ar' => $request->message_ar,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => __('testimonials.updated'),
            'data' => $testimonial
        ], 200);
    }

    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'message' => __('testimonials.deleted'),
        ], 200);
    }
}
