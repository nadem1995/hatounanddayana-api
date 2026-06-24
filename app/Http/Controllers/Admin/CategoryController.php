<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\Admin\Categories\CategoryIndexResource;
use App\Http\Resources\Admin\Categories\CategoryShowResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::query()
            ->search($request->input('search'))
            ->latest()
            ->paginate(10);


        return CategoryIndexResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        //test

        $data['image'] = $request->file('image')->store('categories', 'public');
         Category::create($data);

        return response()->json([
            'message' => __('categories.created'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

        $category = Category::where('slug', $slug)
            ->firstOrFail();

        return new CategoryShowResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);
        return response()->json([
            'message'  => __('categories.updated'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => __('categories.deleted'),
        ], 200);
    }

    public function status(string $id)
    {
        $category = Category::findOrFail($id);
        $category->update([
            'status' => ! $category->status,
        ]);
        return response()->json([
            'message' => $category->status ? __('categories.status_active') : __('categories.status_inactive')
        ]);
    }
}
