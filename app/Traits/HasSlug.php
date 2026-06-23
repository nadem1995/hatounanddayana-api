<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->generateSlugs();
        });

        static::updating(function ($model) {
            $model->generateSlugs();
        });
    }

    /**
     * Creates English + Arabic slugs automatically.
     */
    public function generateSlugs()
    {
        if (isset($this->slug_en) && isset($this->name_en)) {
            $this->slug_en = Str::slug($this->name_en);
        }

        if (isset($this->slug_ar) && isset($this->name_ar)) {
            // Arabic slug (keep Arabic characters)
            $this->slug_ar = Str::slug($this->name_ar, '-', null); 
        }
    }

    /**
     * Let route model binding work with en/ar slug.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug_en', $value)
                    ->orWhere('slug_ar', $value)
                    ->firstOrFail();
    }
}
