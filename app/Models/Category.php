<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $hidden = ['pivot'];

    /* ========= BOOT ========= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name_en);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name_en);
        });
    }

    /* ========= RELATIONS ========= */

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /* ========= ACCESSORS ========= */

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /* ========= SCOPES ========= */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name_en', 'LIKE', "%{$search}%")
                ->orWhere('name_ar', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }


    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar'
            ? $this->name_ar
            : $this->name_en;
    }
}
