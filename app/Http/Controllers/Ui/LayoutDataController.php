<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ui\PageResource;
use App\Models\Banner;
use App\Http\Resources\Ui\BannerResource;
use App\Models\Page;
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
                'pages' => PageResource::collection(
                    Page::where('status', 1)->get()
                ),
            ],
        ]);
    }
}
