<?php

namespace Tests\Feature;

use Database\Seeders\BreedSeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoScenarioSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_accounts_can_open_key_routes(): void
    {
        $this->seed(CitiesTableSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(DemoScenarioSeeder::class);

        $matrix = [
            ['customer@sepasiran.test', 'user.dashboard'],
            ['coach@sepasiran.test', 'coaches.dashboard'],
            ['clinic@sepasiran.test', 'clinic.dashboard'],
            ['breeder@sepasiran.test', 'breeder.dashboard'],
        ];

        foreach ($matrix as [$email, $dash]) {
            $this->post(route('login.store'), [
                'login' => $email,
                'password' => DemoScenarioSeeder::PASSWORD,
            ])->assertRedirect();

            $this->get(route($dash))->assertOk();
            $this->get(route('wallet.index'))->assertOk();
            $this->get(route('account.profile.edit'))->assertOk();
            $this->get(route('account.notifications.index'))->assertOk();

            $this->post(route('logout'));
        }

        $this->post(route('login.store'), [
            'login' => 'breeder@sepasiran.test',
            'password' => DemoScenarioSeeder::PASSWORD,
        ])->assertRedirect();

        $this->get(route('breeder.dogs.listing'))->assertOk();
        $this->get(route('breeder.dashboard-create'))->assertOk();
    }
}