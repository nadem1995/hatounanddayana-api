<?php

namespace App\Http\Resources\Ui;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title' => app()->getLocale() === 'ar'
                ? $this->title_ar
                : $this->title_en,
            'slug'=>$this->slug,
        ];
    }
}
