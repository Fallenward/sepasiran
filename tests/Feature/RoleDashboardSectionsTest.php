<?php

namespace Tests\Feature;

use Database\Seeders\BreedSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardSectionsTest extends TestCase
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

    public function test_customer_has_bookings_and_dogs_sections(): void
    {
        $this->post(route('login.store'), [
            'login' => 'customer@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('درخواست‌های رزرو من', false)
            ->assertSee('سگ‌های من', false);

        $this->get(route('account.bookings.index'))
            ->assertOk()
            ->assertSee('درخواست‌های من', false);

        $this->get(route('account.dogs.index'))->assertOk();
        $this->get(route('account.dogs.create'))->assertOk();
    }

    public function test_coach_listing_is_frontend_and_has_reservations(): void
    {
        $this->post(route('login.store'), [
            'login' => 'coach@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('coaches.listing'))
            ->assertOk()
            ->assertSee('فضای مربی‌گری', false)
            ->assertDontSee('admin.layout', false);

        $this->assertStringEndsWith('/coach/listing', route('coaches.listing'));

        $this->get(route('coaches.reservations'))
            ->assertOk()
            ->assertSee('درخواست‌های دریافتی', false);

        $this->get(route('coaches.courses.index'))
            ->assertOk()
            ->assertSee('دوره‌های من', false);

        $this->get(route('coaches.dashboard'))
            ->assertOk()
            ->assertSee('دوره‌های من', false);
    }

    public function test_clinic_and_breeder_reservation_inboxes(): void
    {
        $this->post(route('login.store'), [
            'login' => 'clinic@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();
        $this->get(route('clinic.reservations'))->assertOk();
        $this->post(route('logout'));

        $this->post(route('login.store'), [
            'login' => 'breeder@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();
        $this->get(route('breeder.reservations'))->assertOk();
        $this->get(route('breeder.dashboard-create'))->assertOk();
    }
}