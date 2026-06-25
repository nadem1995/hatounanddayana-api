<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\Ui\BannerResource;
use Illuminate\Support\Facades\Cache;


class LayoutDataController extends Controller
{

    public function index()
    {
        return response()->json([
            'data' => [
                'banner' => BannerResource::collection(
                    Banner::all()
                ),
            ],
        ]);
    }
}
