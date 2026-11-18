<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_register_pages_render(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('نقش شما', false);
    }

    public function test_customer_can_register_and_login(): void
    {
        Role::query()->firstOrCreate(['name' => 'user']);

        $this->post(route('register.store'), [
            'name' => 'Saeed',
            'family' => 'Askari',
            'phone' => '09121234567',
            'email' => 'saeed@example.com',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'saeed@example.com',
            'phone' => '09121234567',
        ]);
        $this->assertTrue(auth()->user()->hasRole('user'));

        $this->post(route('logout'));
        $this->assertGuest();

        $this->post(route('login.store'), [
            'login' => '09121234567',
            'password' => 'password123',
        ])->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_can_register_as_coach_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Sara',
            'family' => 'Coach',
            'phone' => '09123334455',
            'role' => 'coach',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('coaches.dashboard'));

        $user = User::query()->where('phone', '09123334455')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('coach'));
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_login_keeps_regular_user_session(): void
    {
        Role::query()->firstOrCreate(['name' => 'user']);
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'phone' => '09120001122',
            'password' => bcrypt('secret123'),
        ]);
        $user->roles()->attach(Role::query()->where('name', 'user')->first()->id);

        $this->post(route('login.store'), [
            'login' => 'member@example.com',
            'password' => 'secret123',
        ])->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticatedAs($user);
    }
}