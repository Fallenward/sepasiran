<?php

namespace Tests\Feature;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'email' => 'panel-admin@example.com',
            'is_admin' => true,
        ]);
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin/dashboard')->assertRedirect();
    }

    public function test_admin_dashboard_and_nav_targets_load(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.dashboard.index'))
            ->assertOk()
            ->assertSee('داشبورد مدیریت', false)
            ->assertSee('تیکت‌های اخیر', false)
            ->assertSee('سپاسیران ادمین', false);

        $this->actingAs($admin)->get('/admin')
            ->assertRedirect('/admin/dashboard');

        $this->actingAs($admin)->get(route('admin.support-messages.index'))
            ->assertOk()
            ->assertSee('پیام‌های تماس', false);

        $this->actingAs($admin)->get(route('admin.users.index'))
            ->assertOk();

        $this->actingAs($admin)->get(route('admin.bookings.index'))
            ->assertOk();

        $this->actingAs($admin)->get(route('admin.dogs.index'))
            ->assertOk()
            ->assertSee('سگ‌ها', false);
    }

    public function test_admin_can_delete_support_message(): void
    {
        $admin = $this->admin();
        $msg = SupportMessage::query()->create([
            'name' => 'Test',
            'email' => 'a@b.com',
            'phone' => '0912',
            'message' => 'hello',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.support-messages.destroy', $msg))
            ->assertRedirect();

        $this->assertDatabaseMissing('support_messages', ['id' => $msg->id]);
    }
}