<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = Category::pluck('id')->toArray();

        $colors = [
            ['en' => 'Red', 'ar' => 'أحمر', 'code' => '#FF0000'],
            ['en' => 'Blue', 'ar' => 'أزرق', 'code' => '#0000FF'],
            ['en' => 'Black', 'ar' => 'أسود', 'code' => '#000000'],
        ];

        for ($i = 1; $i <= 50; $i++) {

            // -------------------
            // Create Product
            // -------------------
            $name = 'منتج ' . $i;

            $product = Product::create([
                'name' => $name,   
                'description' => 'وصف المنتج رقم ' . $i,
                'slug' => $this->uniqueSlug(Product::class, 'slug', $name),
                'price' => $faker->numberBetween(50, 500),
                'status' => $faker->boolean(),
                'is_best_seller' => $faker->boolean(),
            ]);

            // -------------------
            // Attach Categories (1–4 per product)
            // -------------------
            $product->categories()->attach(
                $faker->randomElements($categories, rand(1, 4))
            );

            // -------------------
            // Variants + Images
            // -------------------
            foreach ($faker->randomElements($colors, rand(2, 3)) as $color) {

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'color_name' => $color['ar'],
                    'color_code' => $color['code'],
                ]);

                for ($j = 1; $j <= rand(1, 4); $j++) {
                    ProductVariantImage::create([
                        'product_variant_id' => $variant->id,
                        'image' => 'products/' . Str::slug($color['ar']) . "_{$j}.jpg",
                    ]);
                }
            }
        }
    }

    /**
     * Generate unique slug for a model & column
     */
    private function uniqueSlug($model, $column, $value)
    {
        $slug = Str::slug($value);
        $original = $slug;
        $count = 1;

        while ($model::where($column, $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
