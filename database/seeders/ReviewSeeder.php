<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


public function run(): void
{
    $blogs = Blog::all();

    foreach ($blogs as $blog) {
        foreach (range(1, rand(1, 10)) as $i) {
            Review::create([
                'blog_id' => $blog->id,
                'user_id' => null,
                'rating' => rand(1, 5),
                'comment' => fake()->sentence(),
            ]);
        }

        // Optionally update average rating on blog
        $blog->rating = $blog->reviews()->avg('rating');
        $blog->save();
    }
}

}
