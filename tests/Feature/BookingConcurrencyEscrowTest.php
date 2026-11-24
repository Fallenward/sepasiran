<?php

namespace Tests\Feature;

use App\Models\Breed;
use App\Models\Booking;
use App\Models\City;
use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use App\Support\BookingLifecycleService;
use App\Support\BookingPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingConcurrencyEscrowTest extends TestCase
{
    use RefreshDatabase;

    private function seedDirectHeld(): array
    {
        Role::query()->firstOrCreate(['name' => 'coach']);
        Role::query()->firstOrCreate(['name' => 'user']);
        $coach = User::factory()->create(['wallet' => 0]);
        $coach->roles()->attach(Role::query()->where('name', 'coach')->first()->id);
        $customer = User::factory()->create(['wallet' => 5_000_000]);
        $owner = User::factory()->create();
        $city = City::query()->forceCreate(['user_id' => $owner->id, 'name' => 'Shiraz']);
        $breed = Breed::query()->create(['name' => 'Mix', 'description' => 'x']);
        $dog = Dog::query()->create([
            'user_id' => $customer->id,
            'title' => 'Buddy',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 1,
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
        ])->assertCreated();

        $code = $create->json('booking.booking_code');
        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ]);

        return [$customer->fresh(), $coach->fresh(), $code];
    }

    public function test_double_refund_via_cancel_and_reject_does_not_double_credit(): void
    {
        [$customer, $coach, $code] = $this->seedDirectHeld();
        $booking = Booking::query()->where('booking_code', $code)->firstOrFail();
        $life = app(BookingLifecycleService::class);

        $life->cancel($booking);
        $walletAfterCancel = (int) $customer->fresh()->wallet;

        // Second close as reject must not double-credit.
        $life->reject($booking->fresh(), 'reject');
        $this->assertSame($walletAfterCancel, (int) $customer->fresh()->wallet);
        $this->assertSame(0, (int) $coach->fresh()->wallet);
        $this->assertContains($booking->fresh()->status, ['CANCELLED', 'REJECTED']);
    }

    public function test_settle_then_refund_held_is_blocked(): void
    {
        [$customer, $coach, $code] = $this->seedDirectHeld();
        $booking = Booking::query()->where('booking_code', $code)->firstOrFail();
        $pay = app(BookingPaymentService::class);

        $booking->update(['status' => 'ACCEPTED', 'provider_id' => $coach->id]);
        $pay->settleHeld($booking->fresh());
        $this->assertSame('CONFIRMED', $booking->fresh()->status);
        $settledWallet = (int) $coach->fresh()->wallet;
        $this->assertGreaterThan(0, $settledWallet);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        app(BookingLifecycleService::class)->cancel($booking->fresh());
    }

    public function test_duplicate_wallet_pay_is_idempotent(): void
    {
        Role::query()->firstOrCreate(['name' => 'coach']);
        $coach = User::factory()->create(['wallet' => 0]);
        $coach->roles()->attach(Role::query()->where('name', 'coach')->first()->id);
        $customer = User::factory()->create(['wallet' => 3_000_000]);
        $owner = User::factory()->create();
        $city = City::query()->forceCreate(['user_id' => $owner->id, 'name' => 'Qom']);
        $breed = Breed::query()->create(['name' => 'Poodle', 'description' => 'x']);
        $dog = Dog::query()->create([
            'user_id' => $customer->id,
            'title' => 'Tiny',
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
        ])->assertCreated();
        $code = $create->json('booking.booking_code');

        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ]);
        $afterFirst = (int) $customer->fresh()->wallet;

        $this->actingAs($customer)->post(route('frontend.booking.pay.process', $code), [
            'payment_method' => 'wallet',
        ]);
        $this->assertSame($afterFirst, (int) $customer->fresh()->wallet);
    }
}
