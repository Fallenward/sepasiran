<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Support\AppCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AccountProfileNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        Role::query()->firstOrCreate(['name' => 'user']);
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);
        $user->roles()->attach(Role::query()->where('name', 'user')->first()->id);

        return $user->fresh();
    }

    public function test_profile_page_and_update(): void
    {
        Storage::fake('public');
        $user = $this->customer();

        $this->actingAs($user)
            ->get(route('account.profile.edit'))
            ->assertOk()
            ->assertSee('ویرایش پروفایل', false)
            ->assertSee('تصویر پروفایل', false);

        $this->actingAs($user)
            ->post(route('account.profile.update'), [
                'name' => 'Ali',
                'family' => 'Test',
                'phone' => '09129998877',
                'email' => 'ali@example.com',
                'profile_picture' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertRedirect(route('account.profile.edit'));

        $user->refresh();
        $this->assertSame('Ali', $user->name);
        $this->assertNotEmpty($user->profile_picture);
        $this->assertStringContainsString('v=', (string) $user->avatarUrl());
    }

    public function test_notifications_in_header_and_page(): void
    {
        $user = $this->customer();
        Notification::query()->create([
            'user_id' => $user->id,
            'title' => 'رزرو جدید',
            'message' => 'یک رزرو جدید ثبت شد.',
        ]);

        $this->actingAs($user)
            ->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('اعلان‌ها', false)
            ->assertSee('رزرو جدید', false)
            ->assertSee('رفتن به صفحه اعلان‌ها', false);

        $this->actingAs($user)
            ->get(route('account.notifications.index'))
            ->assertOk()
            ->assertSee('یک رزرو جدید ثبت شد.', false);

        $this->actingAs($user)
            ->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('ویرایش پروفایل', false)
            ->assertSee('رزرو جدید', false);
    }

    public function test_header_user_payload_is_cached_and_invalidated(): void
    {
        $user = $this->customer();
        Notification::query()->create([
            'user_id' => $user->id,
            'title' => 'اول',
            'message' => 'پیام اول',
        ]);

        $first = AppCache::userHeader($user);
        $this->assertSame(1, $first['unread']);
        $this->assertTrue(Cache::has(AppCache::userHeaderKey((int) $user->id)));

        $this->actingAs($user)
            ->postJson(route('account.notifications.read-all'))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('unread', 0);

        $this->assertFalse(Cache::has(AppCache::userHeaderKey((int) $user->id)));
        $second = AppCache::userHeader($user->fresh());
        $this->assertSame(0, $second['unread']);
    }

    public function test_mark_single_notification_ajax_without_redirect_body(): void
    {
        $user = $this->customer();
        $note = Notification::query()->create([
            'user_id' => $user->id,
            'title' => 'تک',
            'message' => 'پیام تک',
        ]);

        $this->actingAs($user)
            ->postJson(route('account.notifications.read', $note))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('unread', 0);

        $this->assertNotNull($note->fresh()->read_at);
    }
}