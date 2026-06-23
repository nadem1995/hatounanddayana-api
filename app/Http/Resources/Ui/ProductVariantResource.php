<?php

namespace App\Http\Resources\Ui;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'color_name' => $this->color_name,
            'color_code' => $this->color_code,
            'images' => ProductVariantImageResource::collection(
                $this->whenLoaded('images')
            ),
        ];
    }
}
