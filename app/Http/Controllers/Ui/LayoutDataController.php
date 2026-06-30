<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\Ui\BannerResource;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;


class LayoutDataController extends Controller
{

    public function index()
    {
        $pages = Page::where('status', 1)
            ->get()
            ->map(function ($page) {
                return [
                    'title' => app()->getLocale() === 'ar'
                        ? $page->title_ar
                        : $page->title_en,
                    'slug' => $page->slug,
                    'id'=> $page->id,

                ];
            });
        return response()->json([
            'data' => [
                'banner' => BannerResource::collection(
                    Banner::all()
                ),
                'pages' => $pages
            ],
        ]);
    }
}
