<?php

namespace Tests\Feature;

use App\Models\Course;
use Database\Seeders\BreedSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachCoursesAndBookingHubTest extends TestCase
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

    public function test_coach_can_add_and_edit_courses(): void
    {
        $this->post(route('login.store'), [
            'login' => 'coach@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('coaches.courses.index'))
            ->assertOk()
            ->assertSee('دوره‌های من', false)
            ->assertSee('افزودن دوره', false);

        $this->get(route('coaches.courses.create'))->assertOk();

        $this->post(route('coaches.courses.store'), [
            'title' => 'دوره تست اطاعت',
            'short_description' => 'خلاصه',
            'description' => 'توضیح کامل',
            'price' => 1500000,
            'status' => 'published',
            'is_active' => 1,
        ])->assertRedirect(route('coaches.courses.index'));

        $course = Course::query()->where('title', 'دوره تست اطاعت')->first();
        $this->assertNotNull($course);
        $this->assertSame((int) auth()->id(), (int) $course->user_id);

        $this->get(route('coaches.courses.edit', $course))
            ->assertOk()
            ->assertSee('ویرایش دوره', false);

        $this->put(route('coaches.courses.update', $course), [
            'title' => 'دوره تست اطاعت ویرایش',
            'price' => 1800000,
            'status' => 'published',
            'is_active' => 1,
        ])->assertRedirect(route('coaches.courses.index'));

        $this->assertSame('دوره تست اطاعت ویرایش', $course->fresh()->title);
    }

    public function test_booking_hub_shows_mine_and_inbox_tabs_for_coach(): void
    {
        $this->post(route('login.store'), [
            'login' => 'coach@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('account.bookings.index', ['tab' => 'mine']))
            ->assertOk()
            ->assertSee('درخواست‌های من', false)
            ->assertSee('درخواست‌های دریافتی', false);

        $this->get(route('account.bookings.index', ['tab' => 'inbox']))
            ->assertOk()
            ->assertSee('صندوق دریافتی', false);

        $this->get(route('coaches.reservations'))
            ->assertOk()
            ->assertSee('درخواست‌های دریافتی', false);
    }

    public function test_customer_booking_hub_is_mine_only(): void
    {
        $this->post(route('login.store'), [
            'login' => 'customer@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('account.bookings.index'))
            ->assertOk()
            ->assertSee('درخواست‌های من', false)
            ->assertDontSee('درخواست‌های دریافتی', false);
    }

    public function test_customer_cannot_access_coach_courses(): void
    {
        $this->post(route('login.store'), [
            'login' => 'customer@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('coaches.courses.index'))->assertForbidden();
    }
}