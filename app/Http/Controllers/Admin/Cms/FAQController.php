<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\Cms\FAQResource;
use App\Models\FAQ;

class FAQController extends Controller
{
    public function index()
    {
        return  FAQResource::collection(FAQ::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
        ]);

        FAQ::create([
            'question_en' => $request->question_en,
            'question_ar' => $request->question_ar,
            'answer_en' => $request->answer_en,
            'answer_ar' => $request->answer_ar,
        ]);

        return response()->json([
            'message' => __('FAQ.created'),
        ], 201);
    }

    public function show(string $id)
    {
        $faq = FAQ::findOrFail($id);

        return new FAQResource($faq);
    }

    public function update(Request $request, string $id)
    {
        $faq = FAQ::findOrFail($id);

        $request->validate([
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
        ]);

        $faq->update([
            'question_en' => $request->question_en,
            'question_ar' => $request->question_ar,
            'answer_en' => $request->answer_en,
            'answer_ar' => $request->answer_ar,
        ]);

        return response()->json([
            'message' => __('FAQ.updated'),
        ]);
    }

    public function destroy(string $id)
    {
        $faq = FAQ::findOrFail($id);

        $faq->delete();

        return response()->json([
            'message' => __('FAQ.deleted'),
        ], 200);
    }
}
