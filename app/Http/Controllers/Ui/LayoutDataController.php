<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Ui\BannerResource;


class LayoutDataController extends Controller
{
    public function index()
    {
        $banners = Cache::remember('banners', 3600, function () {
            return Banner::all();
        });

        return response()->json([
            'data' => [
                'banner' => BannerResource::collection($banners),
            ],
        ]);
    }
}

