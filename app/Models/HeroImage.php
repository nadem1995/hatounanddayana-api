<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    protected $fillable = [
        'image'
    ];


    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
