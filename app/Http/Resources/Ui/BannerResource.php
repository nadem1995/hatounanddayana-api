<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'statement' => app()->getLocale() === 'ar'
                ? $this->statement_ar
                : $this->statement_en,
        ];
    }
}
