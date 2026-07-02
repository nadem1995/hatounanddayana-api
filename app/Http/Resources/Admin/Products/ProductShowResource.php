<?php

namespace App\Http\Resources\Admin\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductShowResource extends JsonResource
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
            'name' => [
                'en' => $this->name_en,
                'ar' => $this->name_ar,
            ],
            'description' => [
                'en' => $this->description_en,
                'ar' => $this->description_ar,
            ],
            'price' => $this->price,
            'status' => (bool)$this->status,
            'slug' => $this->slug,

            // Variants
            'variants' => $this->variants->map(fn($v) => [
                'id' => $v->id,

                'color' => [
                    'name' => [
                        'ar' => $v->color_name_ar,
                        'en' => $v->color_name_en,
                    ],
                    'code' => $v->color_code,
                ],
                'images' => $v->images->map(fn($img) => [
                    'id' => $img->id,
                    'image' => $img->image,
                ]),
            ]),
            'categories' => $this->categories->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'image' => $c->image,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
