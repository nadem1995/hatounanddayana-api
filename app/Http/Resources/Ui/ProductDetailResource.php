<?php

namespace App\Http\Resources\Ui;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'price' => $this->price,
            'description'=>$this->description,
            'slug' => $this->slug,
            'color_codes' => $this->whenLoaded(
                'variants',
                fn() => $this->variants->pluck('color_code')->values()
            ),
            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),
        ];
    }
}
