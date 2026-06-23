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
        /* return Cache::remember('layout_data', now()->addHours(6), function () {

            
        }); */

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
