<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingDateValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_past_preferred_date_returns_persian_message(): void
    {
        $role = Role::query()->firstOrCreate(['name' => 'breeder']);
        $breeder = User::factory()->create(['name' => 'Breeder']);
        $breeder->roles()->attach($role->id);

        $customer = User::factory()->create();
        $owner = User::factory()->create();
        $city = City::query()->forceCreate(['user_id' => $owner->id, 'name' => 'Tehran']);
        $breed = Breed::query()->create(['name' => 'Poodle', 'description' => 'x']);
        $dog = Dog::query()->create([
            'user_id' => $customer->id,
            'title' => 'Lua',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 1,
        ]);

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'breeder',
                'fulfillment_mode' => 'direct',
                'provider_ids' => [$breeder->id],
                'preferred_date' => now()->subDay()->toDateString(),
                'contact_name' => 'Saeed',
                'contact_phone' => '09120000000',
                'payment_method' => 'offline',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['preferred_date']);

        $errors = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'breeder',
                'fulfillment_mode' => 'direct',
                'provider_ids' => [$breeder->id],
                'preferred_date' => now()->subDay()->toDateString(),
                'contact_name' => 'Saeed',
                'contact_phone' => '09120000000',
                'payment_method' => 'offline',
            ])
            ->json('errors.preferred_date.0');

        $this->assertIsString($errors);
        $this->assertStringNotContainsString('validation.', $errors);
        $this->assertTrue(
            str_contains($errors, 'تاریخ') || str_contains($errors, 'امروز'),
            'Expected Persian date message, got: '.$errors
        );
    }
}