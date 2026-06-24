<?php

namespace App\Http\Resources\Admin\Categories;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryIndexResource extends JsonResource
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
            'name'=>[
                'en'=>$this->name_en,
                'ar'=>$this->name_ar,
            ],
            'slug' => $this->slug,
            'status' => $this->status,
            'image'=>$this->image,
            'products_count' => $this->products->count()
        ];
    }
}
