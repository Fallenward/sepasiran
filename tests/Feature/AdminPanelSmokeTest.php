<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Dog;
use App\Models\SupportMessage;
use App\Models\Transaction;
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
            ->assertSee('سپاسیران ادمین', false)
            ->assertSee('روند فروش ۶ ماه اخیر', false)
            ->assertSee('assets/js/chart.js', false);

        $this->actingAs($admin)->get('/admin')
            ->assertRedirect('/admin/dashboard');

        $this->actingAs($admin)->get(route('admin.support-messages.index'))
            ->assertOk()
            ->assertSee('پیام‌های تماس', false);

        $this->actingAs($admin)->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('کاربران', false);

        $this->actingAs($admin)->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('رزروها', false);

        $this->actingAs($admin)->get(route('admin.transactions.index'))
            ->assertOk()
            ->assertSee('تراکنش‌ها', false);

        $this->actingAs($admin)->get(route('admin.dogs.index'))
            ->assertOk()
            ->assertSee('سگ‌ها', false);

        $this->actingAs($admin)->get(route('admin.support-tickets.index'))
            ->assertOk()
            ->assertSee('تیکت‌های پشتیبانی', false);
    }

    public function test_admin_show_pages_fill_relations(): void
    {
        $admin = $this->admin();
        $customer = User::factory()->create(['name' => 'CustomerShow', 'email' => 'cshow@example.com']);

        $booking = Booking::query()->create([
            'user_id' => $customer->id,
            'service_type' => 'coach',
            'status' => 'PENDING',
            'price' => 1000,
            'total' => 1000,
            'payment_status' => 'pending',
        ]);

        $trx = Transaction::query()->create([
            'user_id' => $customer->id,
            'transaction_id' => 'TX-TEST-1',
            'status' => 'completed',
            'amount' => 25000,
            'payment_method' => 'online',
        ]);

        $dog = Dog::query()->create([
            'title' => 'DogShowTest',
            'status' => 'available',
            'user_id' => $admin->id,
            'color' => 'brown',
            'age' => 12,
        ]);

        $this->actingAs($admin)->get(route('admin.bookings.show', $booking))
            ->assertOk()
            ->assertSee('جزئیات رزرو', false)
            ->assertSee('CustomerShow', false)
            ->assertDontSee('bookings.index', false);

        $this->actingAs($admin)->get(route('admin.transactions.show', $trx))
            ->assertOk()
            ->assertSee('جزئیات تراکنش', false)
            ->assertSee('CustomerShow', false)
            ->assertSee('25,000', false);

        $this->actingAs($admin)->get(route('admin.dogs.show', $dog))
            ->assertOk()
            ->assertSee('DogShowTest', false)
            ->assertSee('brown', false);

        $this->actingAs($admin)->get(route('admin.users.show', $customer))
            ->assertOk()
            ->assertSee('CustomerShow', false)
            ->assertSee('cshow@example.com', false);
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