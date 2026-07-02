<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'slug',
        'status',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate slug from English name
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name_en);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name_en);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->name_ar
            : $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar'
            ? $this->description_ar
            : $this->description_en;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name_ar', 'LIKE', "%{$search}%")
                ->orWhere('name_en', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }

    public function scopeWithCategories($query, $categories)
    {
        if (empty($categories)) {
            return $query;
        }

        $categoryIds = is_array($categories)
            ? $categories
            : explode(',', $categories);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });
    }


    public function scopeWithVariants($query)
    {
        return $query->with([
            'variants:id,product_id,color_name_ar,color_name_en,color_code',
            'variants.images:id,product_variant_id,image',
        ]);
    }
}
