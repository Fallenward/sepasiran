<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CoursePart;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseDemoSeeder extends Seeder
{
    public function run(): void
    {
        $coach = User::query()->whereHas('roles', fn ($q) => $q->where('name', 'coach'))->first()
            ?? User::query()->orderBy('id')->first();

        if (! $coach) {
            $this->command?->warn('CourseDemoSeeder: no user found, skipped.');

            return;
        }

        $categoryId = Category::query()->value('id') ?? 1;
        $levelId = Level::query()->value('id') ?? 1;

        $catalog = [
            [
                'title' => 'آموزش پایه رفتار سگ',
                'short' => 'از اولین روز تا فرمان‌های پایه، با ویدیوهای کوتاه و تمرین‌های روزانه.',
                'desc' => "این دوره برای صاحبان تازه‌کار طراحی شده است.\nیاد می‌گیرید چگونه ارتباط امن بسازید، فرمان‌های بنشین/بمان را آموزش دهید و عادت‌های روزانه را شکل دهید.",
                'price' => 1200000,
                'discount' => 890000,
                'thumb' => 'images/demo/products/product-01.jpg',
                'featured' => true,
                'parts' => [
                    [
                        'title' => 'شروع و آشنایی',
                        'lessons' => [
                            ['title' => 'خوش‌آمد و اهداف دوره', 'minutes' => 8, 'preview' => true, 'desc' => 'نمای کلی مسیر یادگیری.'],
                            ['title' => 'آماده‌سازی محیط خانه', 'minutes' => 14, 'preview' => true, 'desc' => 'فضای امن و وسایل لازم.'],
                            ['title' => 'زبان بدن سگ', 'minutes' => 18, 'preview' => false, 'desc' => 'نشانه‌های استرس و آرامش.'],
                        ],
                    ],
                    [
                        'title' => 'فرمان‌های پایه',
                        'lessons' => [
                            ['title' => 'فرمان بنشین', 'minutes' => 16, 'preview' => false],
                            ['title' => 'فرمان بمان', 'minutes' => 15, 'preview' => false],
                            ['title' => 'فرمان بیا', 'minutes' => 17, 'preview' => false],
                            ['title' => 'تمرین ترکیبی روزانه', 'minutes' => 20, 'preview' => false, 'desc' => 'برنامه ۱۵ دقیقه‌ای.'],
                        ],
                    ],
                    [
                        'title' => 'رفع مشکلات رایج',
                        'lessons' => [
                            ['title' => 'جویدن وسایل', 'minutes' => 12, 'preview' => false],
                            ['title' => 'پارس زیاد', 'minutes' => 13, 'preview' => false],
                            ['title' => 'جمع‌بندی و گواهی', 'minutes' => 10, 'preview' => false],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'مراقبت از توله تا یک‌سالگی',
                'short' => 'تغذیه، واکسیناسیون، خواب و بازی — نقشه راه ماه‌به‌ماه.',
                'desc' => "راهنمای عملی رشد توله از ورود به خانه تا یک‌سالگی.\nشامل چک‌لیست دامپزشکی، تغذیه، اجتماعی‌سازی و مدیریت انرژی.",
                'price' => 980000,
                'discount' => 750000,
                'thumb' => 'images/demo/products/product-02.jpg',
                'featured' => false,
                'parts' => [
                    [
                        'title' => 'هفته‌های اول',
                        'lessons' => [
                            ['title' => 'روز اول در خانه', 'minutes' => 11, 'preview' => true],
                            ['title' => 'خواب و قفس امن', 'minutes' => 14, 'preview' => false],
                            ['title' => 'تغذیه توله', 'minutes' => 16, 'preview' => false],
                        ],
                    ],
                    [
                        'title' => 'سلامت و دامپزشک',
                        'lessons' => [
                            ['title' => 'برنامه واکسن', 'minutes' => 15, 'preview' => false],
                            ['title' => 'انگل و پیشگیری', 'minutes' => 12, 'preview' => false],
                            ['title' => 'نشانه‌های هشدار', 'minutes' => 13, 'preview' => false],
                        ],
                    ],
                    [
                        'title' => 'اجتماعی‌سازی',
                        'lessons' => [
                            ['title' => 'آشنایی با انسان و سگ', 'minutes' => 18, 'preview' => false],
                            ['title' => 'بازی کنترل‌شده', 'minutes' => 14, 'preview' => false],
                            ['title' => 'چک‌لیست یک‌سالگی', 'minutes' => 9, 'preview' => true],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'پیاده‌روی حرفه‌ای و کنترل بند',
                'short' => 'پیاده‌روی بدون کشیدن بند، تمرکز در خیابان و مدیریت حواس‌پرتی.',
                'desc' => "برای صاحبان سگ‌های پرانرژی که پیاده‌روی سخت شده است.\nتکنیک‌های بند، تغییر جهت، تمرکز و تمرین‌های شهری.",
                'price' => 650000,
                'discount' => 0,
                'thumb' => 'images/demo/products/product-03.jpg',
                'featured' => true,
                'parts' => [
                    [
                        'title' => 'ابزار و وضعیت بدن',
                        'lessons' => [
                            ['title' => 'انتخاب بند و هارنس', 'minutes' => 10, 'preview' => true],
                            ['title' => 'وضعیت دست و پا', 'minutes' => 12, 'preview' => false],
                        ],
                    ],
                    [
                        'title' => 'تکنیک‌های کنترل',
                        'lessons' => [
                            ['title' => 'توقف و شروع', 'minutes' => 14, 'preview' => false],
                            ['title' => 'تغییر جهت', 'minutes' => 13, 'preview' => false],
                            ['title' => 'مدیریت حواس‌پرتی', 'minutes' => 16, 'preview' => false],
                            ['title' => 'تمرین خیابان شلوغ', 'minutes' => 18, 'preview' => false],
                            ['title' => 'جمع‌بندی نهایی', 'minutes' => 8, 'preview' => false],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($catalog as $index => $item) {
            $slugBase = Str::slug($item['title']);
            if ($slugBase === '') {
                $slugBase = 'course-demo';
            }
            $slug = $slugBase.'-demo-'.($index + 1);
            $duration = 0;
            foreach ($item['parts'] as $part) {
                foreach ($part['lessons'] as $lesson) {
                    $duration += (int) ($lesson['minutes'] ?? 0);
                }
            }

            $course = Course::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $item['title'],
                    'short_description' => $item['short'],
                    'description' => $item['desc'],
                    'thumbnail' => $item['thumb'],
                    'user_id' => $coach->id,
                    'category_id' => $categoryId,
                    'level_id' => $levelId,
                    'price' => $item['price'],
                    'discount_price' => $item['discount'] ?: null,
                    'status' => 'published',
                    'is_active' => 1,
                    'is_featured' => $item['featured'],
                    'has_certificate' => true,
                    'video_demo_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'duration' => $duration,
                    'view_count' => 40 + ($index * 17),
                ]
            );

            CourseLesson::query()->where('course_id', $course->id)->delete();
            CoursePart::query()->where('course_id', $course->id)->delete();

            foreach ($item['parts'] as $partOrder => $partData) {
                $part = CoursePart::query()->create([
                    'course_id' => $course->id,
                    'title' => $partData['title'],
                    'sort_order' => $partOrder + 1,
                ]);

                foreach ($partData['lessons'] as $lessonOrder => $lessonData) {
                    CourseLesson::query()->create([
                        'course_id' => $course->id,
                        'course_part_id' => $part->id,
                        'title' => $lessonData['title'],
                        'description' => $lessonData['desc'] ?? null,
                        'sort_order' => $lessonOrder + 1,
                        'duration_minutes' => $lessonData['minutes'] ?? null,
                        'is_preview' => (bool) ($lessonData['preview'] ?? false),
                        'video_url' => ($lessonData['preview'] ?? false)
                            ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'
                            : null,
                    ]);
                }
            }
        }

        $this->command?->info('CourseDemoSeeder: seeded '.count($catalog).' demo courses with parts/lessons.');
    }
}