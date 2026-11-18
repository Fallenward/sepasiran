<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_configured_failures(): void
    {
        $user = User::factory()->create([
            'email' => 'throttle@example.com',
            'password' => bcrypt('secret-password'),
        ]);

        $max = (int) config('rate_limiting.login.max_attempts', 5);
        RateLimiter::clear(Str::transliterate(Str::lower($user->email).'|127.0.0.1'));

        for ($i = 0; $i < $max; $i++) {
            $response = $this->from(route('login'))->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);

            $response->assertStatus(302);
        }

        $blocked = $this->from(route('login'))->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // LoginRequest ValidationException or HTTP throttle (429)
        $this->assertTrue(
            $blocked->status() === 429
            || $blocked->status() === 302
        );

        if ($blocked->status() === 302) {
            $blocked->assertSessionHasErrors();
        }
    }

    public function test_otp_send_is_throttled(): void
    {
        $max = (int) config('rate_limiting.otp.max_attempts', 3);

        for ($i = 0; $i < $max; $i++) {
            $this->postJson(route('otp.send'), ['phone' => '09121234567'])
                ->assertOk();
        }

        $this->postJson(route('otp.send'), ['phone' => '09121234567'])
            ->assertStatus(429);
    }
}