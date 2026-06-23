<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\Admin\Marketing\BannerIndexResource;
use App\Http\Requests\Admin\Marketing\Banners\StoreBannerRequest;
use App\Http\Requests\Admin\Marketing\Banners\UpdateBannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BannerIndexResource::collection(
            Banner::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        $banner = Banner::create([
            'statement' => $request->statement
        ]);

        return response()->json([
            'message' => __('banners.created')
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request,$id)
    {
        $banner = Banner::findOrFail($id);

        $banner->update([
            'statement' => $request->statement
        ]);

        return response()->json([
            'message' => __('banners.updated')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return response()->json([
            'message' => __('banners.deleted'),
        ], 200);
    }
}
