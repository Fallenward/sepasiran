<?php

namespace Tests\Feature;

use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMyDogsSectionTest extends TestCase
{
    use RefreshDatabase;

    private function roleUser(string $role): User
    {
        Role::query()->firstOrCreate(['name' => $role]);
        if ($role !== 'user') {
            Role::query()->firstOrCreate(['name' => 'user']);
        }
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('name', $role)->first()->id);

        return $user->fresh();
    }

    public function test_customer_dashboard_shows_my_dogs_section(): void
    {
        $user = $this->roleUser('user');
        Dog::query()->create([
            'user_id' => $user->id,
            'title' => 'Dashboard Pup',
            'status' => 'owned',
            'age' => 4,
        ]);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('سگ‌های من', false)
            ->assertSee('Dashboard Pup', false)
            ->assertSee(route('account.dogs.index'), false)
            ->assertSee(route('account.dogs.create'), false);
    }

    public function test_coach_dashboard_shows_my_dogs_section_and_shortcut(): void
    {
        $coach = $this->roleUser('coach');

        $this->actingAs($coach)
            ->get(route('coaches.dashboard'))
            ->assertOk()
            ->assertSee('سگ‌های من', false)
            ->assertSee('ثبت اولین سگ', false)
            ->assertSee(route('account.dogs.index'), false);
    }
}