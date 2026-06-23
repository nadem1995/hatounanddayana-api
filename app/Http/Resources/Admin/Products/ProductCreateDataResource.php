<?php

namespace App\Http\Resources\Admin\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCreateDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'categories' => $this['categories']->map(fn($category) => [
                'id' => $category->id,
                'name' => $category->name
            ]),
        ];
    }
}
