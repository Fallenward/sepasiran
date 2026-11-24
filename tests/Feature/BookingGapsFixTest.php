<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\ProviderService;
use App\Models\Role;
use App\Models\User;
use App\Support\BookingLifecycleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingGapsFixTest extends TestCase
{
    use RefreshDatabase;

    private function roleUser(string $role, array $attrs = []): User
    {
        Role::query()->firstOrCreate(['name' => $role]);
        if ($role !== 'user') {
            Role::query()->firstOrCreate(['name' => 'user']);
        }
        $user = User::factory()->create(array_merge(['wallet' => 0], $attrs));
        $user->roles()->attach(Role::query()->where('name', $role)->first()->id);

        return $user->fresh();
    }

    private function city(): City
    {
        $owner = User::factory()->create();

        return City::query()->forceCreate(['user_id' => $owner->id, 'name' => 'Tehran']);
    }

    private function dogFor(User $user): Dog
    {
        $breed = Breed::query()->firstOrCreate(['name' => 'Husky'], ['description' => 'x']);

        return Dog::query()->create([
            'user_id' => $user->id,
            'title' => 'Rocky',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 3,
        ]);
    }

    private function service(User $coach, array $attrs = []): ProviderService
    {
        return ProviderService::query()->create(array_merge([
            'user_id' => $coach->id,
            'service_type' => 'coach',
            'name' => 'Private session',
            'description' => 'One-on-one',
            'base_price' => 1_000_000,
            'currency' => 'IRT',
            'duration_minutes' => 60,
            'training_surcharges' => ['advanced' => 250000],
            'is_active' => true,
        ], $attrs));
    }

    public function test_direct_store_requires_provider_service_when_provider_has_active_services(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 5_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);
        $this->service($coach);

        $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
            'payment_method' => 'wallet',
        ])->assertStatus(422)->assertJsonValidationErrors(['provider_service_id']);
    }

    public function test_rejects_service_from_another_provider_and_inactive_service(): void
    {
        $coach = $this->roleUser('coach');
        $other = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 5_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);
        $mine = $this->service($coach);
        $theirs = $this->service($other, ['name' => 'Other']);
        $inactive = $this->service($coach, ['name' => 'Off', 'is_active' => false, 'base_price' => 500000]);

        $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'provider_service_id' => $theirs->id,
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertStatus(422)->assertJsonValidationErrors(['provider_service_id']);

        $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'provider_service_id' => $inactive->id,
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertStatus(422)->assertJsonValidationErrors(['provider_service_id']);

        $ok = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'advanced',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'provider_service_id' => $mine->id,
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();

        // base 1_000_000 + advanced surcharge 250_000 + tax
        $total = (int) $ok->json('booking.total');
        $this->assertGreaterThan(1_000_000, $total);
        $this->assertSame($mine->id, (int) $ok->json('booking.provider_service_id'));
    }

    public function test_provider_services_api_lists_only_active_for_provider(): void
    {
        $coach = $this->roleUser('coach');
        $other = $this->roleUser('coach');
        $this->service($coach, ['name' => 'A']);
        $this->service($coach, ['name' => 'B', 'is_active' => false]);
        $this->service($other, ['name' => 'C']);

        $res = $this->getJson(route('frontend.booking.api.provider-services', [
            'provider_id' => $coach->id,
            'service_type' => 'coach',
        ]))->assertOk();

        $names = collect($res->json('results'))->pluck('name')->all();
        $this->assertContains('A', $names);
        $this->assertNotContains('B', $names);
        $this->assertNotContains('C', $names);
    }

    public function test_api_cancel_after_pay_refunds_wallet_once(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 3_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();

        $code = $create->json('booking.booking_code');
        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ])->assertRedirect();

        $afterPay = (int) $customer->fresh()->wallet;
        $this->assertLessThan(3_000_000, $afterPay);

        $this->actingAs($customer)->postJson(route('frontend.booking.api.cancel', $code))->assertOk();
        $this->assertSame(3_000_000, (int) $customer->fresh()->wallet);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'CANCELLED',
            'payment_status' => 'refunded',
            'settlement_status' => 'refunded',
        ]);
        $this->assertSame(0, (int) $coach->fresh()->wallet);

        // Idempotent second cancel
        $this->actingAs($customer)->postJson(route('frontend.booking.api.cancel', $code))->assertOk();
        $this->assertSame(3_000_000, (int) $customer->fresh()->wallet);
    }

    public function test_reject_sets_rejected_and_refunds(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 2_500_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();
        $code = $create->json('booking.booking_code');

        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ]);

        $this->actingAs($coach)->postJson(route('frontend.booking.api.respond', $code), [
            'action' => 'reject',
            'reason' => 'Capacity full',
        ])->assertOk();

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'REJECTED',
            'payment_status' => 'refunded',
            'settlement_status' => 'refunded',
        ]);
        $this->assertSame(2_500_000, (int) $customer->fresh()->wallet);
        $this->assertSame(0, (int) $coach->fresh()->wallet);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
        ]);
    }

    public function test_unpaid_cancel_does_not_create_refund_transaction(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 1_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();
        $code = $create->json('booking.booking_code');

        $this->actingAs($customer)->postJson(route('frontend.booking.api.cancel', $code))->assertOk();
        $this->assertSame(1_000_000, (int) $customer->fresh()->wallet);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'CANCELLED',
            'payment_status' => 'pending',
        ]);
    }

    public function test_cannot_pay_rejected_booking(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 5_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();
        $code = $create->json('booking.booking_code');

        $this->actingAs($coach)->postJson(route('frontend.booking.api.respond', $code), [
            'action' => 'reject',
        ])->assertOk();

        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ])->assertSessionHasErrors();

        $this->assertSame(5_000_000, (int) $customer->fresh()->wallet);
    }

    public function test_lifecycle_idempotent_double_close(): void
    {
        $coach = $this->roleUser('coach');
        $customer = $this->roleUser('user', ['wallet' => 2_000_000]);
        $city = $this->city();
        $dog = $this->dogFor($customer);

        $create = $this->actingAs($customer)->postJson(route('frontend.booking.api.store'), [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ])->assertCreated();
        $code = $create->json('booking.booking_code');
        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ]);

        $booking = \App\Models\Booking::query()->where('booking_code', $code)->firstOrFail();
        $life = app(BookingLifecycleService::class);
        $life->reject($booking, 'reject');
        $life->reject($booking->fresh(), 'reject');
        $this->assertSame(2_000_000, (int) $customer->fresh()->wallet);
        $this->assertSame('REJECTED', $booking->fresh()->status);
    }
}
