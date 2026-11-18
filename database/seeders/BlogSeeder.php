<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        Blog::query()->delete();

        $posts = [
            [
                'title' => 'چطور پیاده‌روی روزانه سگ را منظم کنیم؟',
                'description' => 'برنامه ساده برای انرژی‌سوزی و آرامش بیشتر در خانه.',
                'image' => 'images/demo/blogs/blog-01.jpg',
                'type' => 'blog',
            ],
            [
                'title' => 'تغذیه سگ بالغ: اشتباه‌های رایج',
                'description' => 'نکته‌های کاربردی برای انتخاب غذا و جلوگیری از اضافه‌وزن.',
                'image' => 'images/demo/blogs/blog-02.jpg',
                'type' => 'blog',
            ],
            [
                'title' => 'آماده‌سازی خانه برای توله جدید',
                'description' => 'چک‌لیست وسایل ضروری در هفته اول ورود توله.',
                'image' => 'images/demo/blogs/blog-03.jpg',
                'type' => 'news',
            ],
            [
                'title' => 'نشانه‌های نیاز به ویزیت کلینیک',
                'description' => 'چه علائمی را نباید نادیده بگیرید.',
                'image' => 'images/demo/blogs/blog-04.jpg',
                'type' => 'support',
            ],
            [
                'title' => 'بازی‌های کوتاه برای آپارتمان',
                'description' => '۵ بازی کم‌جا برای تخلیه انرژی سگ‌های شهری.',
                'image' => 'images/demo/blogs/blog-01.jpg',
                'type' => 'blog',
            ],
            [
                'title' => 'انتخاب مربی مناسب',
                'description' => 'چه سوال‌هایی قبل از رزرو جلسه بپرسید.',
                'image' => 'images/demo/blogs/blog-02.jpg',
                'type' => 'news',
            ],
        ];

        foreach ($posts as $i => $post) {
            $slug = Str::slug($post['title']) ?: 'post-' . ($i + 1);
            Blog::query()->updateOrCreate(
                ['slug' => $slug . '-' . ($i + 1)],
                [
                    'type' => $post['type'],
                    'title' => $post['title'],
                    'content' => $post['description'] . "\n\n" . 'این مطلب نمونه برای پیش‌نمایش سپاسیران است.',
                    'description' => $post['description'],
                    'image' => $post['image'],
                    'category_id' => 1,
                    'author_id' => 1,
                    'rating' => 4.5,
                    'views' => rand(120, 1800),
                    'likes' => rand(10, 120),
                    'status' => 'published',
                    'comments_enabled' => true,
                ]
            );
        }
    }
}