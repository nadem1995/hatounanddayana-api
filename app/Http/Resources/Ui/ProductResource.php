<?php

namespace App\Http\Resources\Ui;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstVariant = $this->variants->first();
        $firstImage = $firstVariant?->images->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description'=>$this->description,
            'slug' => $this->slug,
            'image' => $firstImage?->image,
            'color_codes' => $this->whenLoaded(
                'variants',
                fn() => $this->variants->pluck('color_code')->values()
            ),
        ];
    }
}
