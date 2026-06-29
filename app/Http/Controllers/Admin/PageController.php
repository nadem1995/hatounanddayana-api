<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{

    // GET all pages
    public function index()
    {
        return Page::latest()->get();
    }

    // STORE page
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $validated['slug'] = $this->generateSlug($validated['title_en']);

        $page = Page::create($validated);

        return response()->json([
            'message' => __('pages.created'),
            'data' => $page
        ], 201);
    }

    // SHOW page
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return $page;
    }
    // UPDATE page
    public function update(Request $request, string $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'status' => 'required|boolean',
        ]);

        if ($page->title_en !== $validated['title_en']) {
            $validated['slug'] = $this->generateSlug(
                $validated['title_en'],
                $page->id
            );
        }

        $page->update($validated);

        return response()->json([
            'message' => __('pages.updated'),
            'data' => $page
        ]);
    }

    // DELETE page
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'message' => __('pages.deleted'),
        ], 200);
    }

    // TOGGLE STATUS
    public function status(string $id)
    {
        $page = Page::findOrFail($id);
        $page->update([
            'status' => ! $page->status,
        ]);
        return response()->json([
            'message' => $page->status ? __('pages.status_active') : __('pages.status_inactive')
        ]);
    }

    // GENERATE UNIQUE SLUG
    private function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);

        $query = Page::where('slug', 'LIKE', "{$slug}%");

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $count = $query->count();

        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        return $slug;
    }
}
