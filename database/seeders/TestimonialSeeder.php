<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('en_US');

        for ($i = 1; $i <= 13; $i++) {
            Testimonial::create([
                'name'      => $faker->name(),
                'source'   => $faker->company(),
                'message'   => $faker->paragraphs(rand(1, 2), true),
                'rating'    => $faker->numberBetween(1, 5),
            ]);
        }
    }
}
