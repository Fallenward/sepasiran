<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // Make sure a user with this ID exists
        $categoryId = 1; // Adjust as needed
        $levelId = 1; // Adjust as needed

        foreach (range(1, 10) as $i) {
            Course::create([
                'title' => "Sample Course $i",
                'slug' => Str::slug("Sample Course $i") . '-' . $i,
                'short_description' => "This is a short description for Course $i.",
                'description' => "Detailed content of Course $i...",
                'thumbnail' => "images/sample$i.jpg", // optional
                'user_id' => $userId,
                'category_id' => $categoryId,
                'level_id' => $levelId,
                'price' => 1000 + ($i * 100),
                'discount_price' => 800 + ($i * 50),
                'status' => 'published',
                'is_featured' => $i % 2 == 0,
                'has_certificate' => true,
                'video_demo_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 60 * $i,
                'view_count' => rand(0, 500),
            ]);
        }
    }
}
