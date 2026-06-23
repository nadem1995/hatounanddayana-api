<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaSetting extends Model
{
    protected $fillable = [
        'name',
        'url',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
