<?php

namespace App\Http\Resources\Admin\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'status' => (bool) $this->status,
            'is_best_seller' => (bool) $this->is_best_seller,
            'slug' => $this->slug,
        ];
    }
}
