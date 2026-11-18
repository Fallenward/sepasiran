<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardsTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);
        $roleIds = [];
        $roleIds[] = Role::query()->firstOrCreate(['name' => $role])->id;
        if ($role !== 'user') {
            $roleIds[] = Role::query()->firstOrCreate(['name' => 'user'])->id;
        }
        $user->roles()->sync($roleIds);

        return $user->fresh();
    }

    public function test_customer_dashboard_renders(): void
    {
        $user = $this->userWithRole('user');
        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('رزرو جدید', false)
            ->assertSee('نمودار تراکنش‌های مالی', false)
            ->assertDontSee('اقدام بعدی', false);
    }

    public function test_coach_dashboard_renders(): void
    {
        $user = $this->userWithRole('coach');
        $this->actingAs($user)
            ->get(route('coaches.dashboard'))
            ->assertOk()
            ->assertSee('داشبورد مربی', false);
    }

    public function test_clinic_dashboard_renders(): void
    {
        $user = $this->userWithRole('clinic');
        $this->actingAs($user)
            ->get(route('clinic.dashboard'))
            ->assertOk()
            ->assertSee('داشبورد کلینیک', false);
    }

    public function test_breeder_dashboard_renders(): void
    {
        $user = $this->userWithRole('breeder');
        $this->actingAs($user)
            ->get(route('breeder.dashboard'))
            ->assertOk()
            ->assertSee('افزودن سگ', false);
    }

    public function test_dashboard_route_helper_prefers_provider_role(): void
    {
        $user = $this->userWithRole('breeder');
        $this->assertSame(route('breeder.dashboard'), $user->dashboardRoute());
    }
}
