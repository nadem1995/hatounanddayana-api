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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'status' => (bool) $this->status,
            'is_best_seller' => (bool) $this->is_best_seller,
            'slug' => $this->slug,
            'variants' => $this->variants->map(fn($v) => [
                'id' => $v->id,
                'color' => [
                    'name' => $v->color_name,
                    'code' => $v->color_code
                ],
                'stock' => $v->stock,
                'images' => $v->images,
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
