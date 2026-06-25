<?php

namespace App\Http\Resources\Admin\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FAQResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
        'id' => $this->id,

        'question' => [
            'en' => $this->question_en,
            'ar' => $this->question_ar,
        ],

        'answer' => [
            'en' => $this->answer_en,
            'ar' => $this->answer_ar,
        ],

        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
    }
}
