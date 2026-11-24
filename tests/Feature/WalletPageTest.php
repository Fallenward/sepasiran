<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_uses_frontend_shell(): void
    {
        Role::query()->firstOrCreate(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('name', 'user')->first()->id);

        $this->actingAs($user)
            ->get(route('wallet.index'))
            ->assertOk()
            ->assertSee('کیف پول', false)
            ->assertSee('نمودار تراکنش‌های مالی', false)
            ->assertDontSee('admin.layout', false);
    }
}
