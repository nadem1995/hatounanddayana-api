<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\SocialMediaSetting;
use Illuminate\Support\Facades\Cache;


class LayoutDataController extends Controller
{

    public function index()
    {
        return response()->json([
            'data' => [
                'banner' => Banner::select('id', 'statement')->get(),
                'social_links' => SocialMediaSetting::where('status', true)
                    ->select('name', 'url')
                    ->get(),
            ],
        ]);
    }
}
