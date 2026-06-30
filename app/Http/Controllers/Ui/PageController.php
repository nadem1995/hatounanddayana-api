<?php

namespace App\Http\Controllers\Ui;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => [
                'id' => $page->id,
                'title' => app()->getLocale() === 'ar'
                    ? $page->title_ar
                    : $page->title_en,
                'slug' => $page->slug,
                'content' => app()->getLocale() === 'ar'
                    ? $page->content_ar
                    : $page->content_en,
            ],
        ]);
    }
}
