<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $types = ['blog', 'news', 'support', 'about-us'];

        foreach (range(1, 20) as $i) {
            Blog::create([
                'type' => $faker->randomElement($types),
                'title' => $faker->sentence,
                'slug' => $faker->slug . '-' . $i,
                'content' => $faker->paragraphs(3, true),
                'description' => $faker->paragraph,
                'image' => 'images/blog.svg',
                'category_id' => rand(1, 6),
                'author_id' => rand(1, 5),
                'rating' => $faker->randomFloat(2, 1, 5),
                'views' => rand(100, 5000),
                'likes' => rand(0, 500),
                'status' => $faker->randomElement(['draft', 'published']),
                'comments_enabled' => $faker->boolean,
            ]);
        }
    }
}
