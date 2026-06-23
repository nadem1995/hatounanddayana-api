<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $fakerEn = \Faker\Factory::create('en_US'); // English faker
        for ($i = 1; $i <= 50; $i++) {
            $name = ucfirst($fakerEn->unique()->word());
            Category::create([
                'name'  => $name,
                'slug'  => Str::slug($name),
                'image'    => $fakerEn->imageUrl(400, 400, 'cats', true),
                'status'   => $fakerEn->boolean(),
            ]);
        }
    }
}
