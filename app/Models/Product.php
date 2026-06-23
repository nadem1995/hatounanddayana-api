<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'price',
        'is_best_seller',
    ];


    protected $casts = [
        'price' => 'float',
        'status' => 'boolean',
        'is_best_seller' => 'boolean',
    ];


    protected static function boot()
    {
        parent::boot();

        // clear dashboard cache
        $clearCache = fn() => Cache::forget('admin.dashboard');

        static::created($clearCache);
        static::updated($clearCache);
        static::deleted($clearCache);
        static::restored($clearCache);

        // generate slugs
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    /* ========= RELATIONS ========= */


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    /* ========= SCOPES ========= */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->whereAny(
            ['name', 'slug'],
            'LIKE',
            "%{$search}%"
        );
    }


    public function scopeWithCategories($query, $categories)
    {
        if (empty($categories)) {
            return $query;
        }

        // accept array OR comma-separated string
        $categoryIds = is_array($categories)
            ? $categories
            : explode(',', $categories);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });
    }


    public function scopeWithBestSeller($query, $bestSeller)
    {
        if ($bestSeller === null) {
            return $query;
        }

        $isBestSeller = filter_var($bestSeller, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($isBestSeller === null) {
            return $query;
        }

        return $query->where('is_best_seller', $isBestSeller);
    }


    public function scopeWithVariants($query)
    {
        return $query->with([
            'variants:id,product_id,color_name,color_code',
            'variants.images:id,product_variant_id,image'
        ]);
    }
}
