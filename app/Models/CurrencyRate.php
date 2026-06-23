<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CurrencyRate extends Model
{
    protected $fillable = [
        'SYP_rate',
    ];
    protected $casts = [
    'SYP_rate' => 'float',
];

    protected static function booted()
    {
        static::updated(fn () => Cache::forget('admin.dashboard'));
    }
}
