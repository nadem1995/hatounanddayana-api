<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaSetting;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    // ✅ Get all social media
    public function index()
    {
        $data = SocialMediaSetting::all();
        return response()->json([
            'data' => $data
        ]);
    }

    // ✅ Store new social media
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:social_media_settings,name',
            'url' => 'required|url',
            'status' => 'boolean'
        ]);

        $social = SocialMediaSetting::create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'status' => $validated['status'] ?? true,
        ]);

        return response()->json([
            'message' => __('social_media.created'),
        ], 201);
    }

    // ✅ Update
    public function update(Request $request, $id)
    {
        $social = SocialMediaSetting::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:social_media_settings,name,' . $id,
            'url' => 'nullable|url',
            'status' => 'boolean'
        ]);

        $social->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->status ?? $social->status,
        ]);

        return response()->json([
            'message' => __('social_media.updated'),
        ]);
    }

    // ✅ Delete
    public function destroy($id)
    {
        $social = SocialMediaSetting::findOrFail($id);
        $social->delete();

        return response()->json([
            'message' => __('social_media.deleted')
        ]);
    }

    public function status($id)
    {
        $social = SocialMediaSetting::findOrFail($id);
        $social->status = !$social->status;
        $social->save();

        return response()->json([
            'message' => $social->status? __('social_media.status_active') : __('social_media.status_inactive')
        ]);
    }
}
