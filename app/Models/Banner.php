<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Banner extends Model
{
    protected $fillable = ['statement_en', 'statement_ar'];

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('banners_en');
            Cache::forget('banners_ar');
        });

        static::updated(function () {
            Cache::forget('banners_en');
            Cache::forget('banners_ar');
        });

        static::deleted(function () {
            Cache::forget('banners_en');
            Cache::forget('banners_ar');
        });
    }
}
