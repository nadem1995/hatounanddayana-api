<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Str;

class SlugHelper
{
    public static function generateProductSlug(string $name, string $column): string
    {
        $baseSlug = Str::slug($name, '-');
        $slug = $baseSlug;
        $count = 1;

        while (Product::where($column, $slug)->exists()) {
            $slug = $baseSlug.'-'.$count;
            $count++;
        }
        

        return $slug;
    }
}
