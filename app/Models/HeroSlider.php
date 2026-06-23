<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    protected $fillable = [
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function getImageAttribute($value)
    {
        return $value
            ? asset('storage/' . $value)
            : null;
    }
}