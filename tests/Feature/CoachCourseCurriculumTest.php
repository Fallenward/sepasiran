<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CoursePart;
use Database\Seeders\BreedSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CoachCourseCurriculumTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CitiesTableSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(DemoScenarioSeeder::class);
    }

    public function test_coach_can_create_course_with_parts_lessons_and_video(): void
    {
        Storage::fake('public');

        $this->post(route('login.store'), [
            'login' => 'coach@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('coaches.courses.create'))
            ->assertOk()
            ->assertSee('بخش‌ها و درس‌ها', false)
            ->assertSee('افزودن بخش', false);

        $video = UploadedFile::fake()->create('lesson1.mp4', 1024, 'video/mp4');

        $response = $this->post(route('coaches.courses.store'), [
            'title' => 'دوره ویدیویی اطاعت',
            'price' => 2000000,
            'status' => 'published',
            'is_active' => 1,
            'parts' => [
                [
                    'title' => 'مقدمات',
                    'lessons' => [
                        [
                            'title' => 'جلسه اول',
                            'description' => 'آشنایی',
                            'duration_minutes' => 12,
                            'is_preview' => 1,
                            'video' => $video,
                        ],
                        [
                            'title' => 'جلسه دوم',
                            'duration_minutes' => 18,
                        ],
                    ],
                ],
                [
                    'title' => 'پیشرفته',
                    'lessons' => [
                        [
                            'title' => 'فرمان‌های پیشرفته',
                            'duration_minutes' => 20,
                        ],
                    ],
                ],
            ],
        ]);

        $course = Course::query()->where('title', 'دوره ویدیویی اطاعت')->first();
        $this->assertNotNull($course);
        $response->assertRedirect(route('coaches.courses.edit', $course));

        $this->assertSame(2, CoursePart::query()->where('course_id', $course->id)->count());
        $this->assertSame(3, CourseLesson::query()->where('course_id', $course->id)->count());

        $firstLesson = CourseLesson::query()
            ->where('course_id', $course->id)
            ->where('title', 'جلسه اول')
            ->first();
        $this->assertNotNull($firstLesson);
        $this->assertNotEmpty($firstLesson->video_path);
        $this->assertTrue((bool) $firstLesson->is_preview);
        Storage::disk('public')->assertExists($firstLesson->video_path);

        $html = $this->get(route('coaches.courses.index'))->assertOk()->getContent();
        $this->assertStringContainsString('بخش', $html);
        $this->assertStringContainsString('درس', $html);
    }
}