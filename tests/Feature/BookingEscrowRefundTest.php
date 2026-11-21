<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingEscrowRefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_reject_after_pay_refunds_customer_wallet(): void
    {
        $role = Role::query()->firstOrCreate(['name' => 'coach']);
        $coach = User::factory()->create(['wallet' => 0]);
        $coach->roles()->attach($role->id);
        $customer = User::factory()->create(['wallet' => 2_000_000]);
        $owner = User::factory()->create();
        $city = City::query()->forceCreate(['user_id' => $owner->id, 'name' => 'Isfahan']);
        $breed = Breed::query()->create(['name' => 'Lab', 'description' => 'x']);
        $dog = Dog::query()->create([
            'user_id' => $customer->id,
            'title' => 'Max',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 2,
        ]);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
            'payment_method' => 'wallet',
        ])->assertCreated();

        $code = $create->json('booking.booking_code');
        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ])->assertRedirect();

        $customer->refresh();
        $this->assertLessThan(2_000_000, (int) $customer->wallet);

        $this->actingAs($coach)->postJson(route('frontend.booking.api.respond', $code), [
            'action' => 'reject',
        ])->assertOk();

        $customer->refresh();
        $coach->refresh();
        $this->assertSame(2_000_000, (int) $customer->wallet);
        $this->assertSame(0, (int) $coach->wallet);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'REJECTED',
            'payment_status' => 'refunded',
            'settlement_status' => 'refunded',
        ]);
    }
}