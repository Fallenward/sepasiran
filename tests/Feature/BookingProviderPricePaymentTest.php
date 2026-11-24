<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\ProviderServicePrice;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingProviderPricePaymentTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(?array $attrs = []): User
    {
        $role = Role::query()->firstOrCreate(['name' => 'coach']);
        $coach = User::factory()->create(array_merge(['name' => 'Coach', 'family' => 'One', 'wallet' => 0], $attrs));
        $coach->roles()->attach($role->id);

        return $coach;
    }

    private function makeCity(string $name = 'Tehran'): City
    {
        $owner = User::factory()->create();

        return City::query()->forceCreate([
            'user_id' => $owner->id,
            'name' => $name,
        ]);
    }

    private function makeDog(User $owner): Dog
    {
        $breed = Breed::query()->create(['name' => 'Husky', 'description' => 'x']);

        return Dog::query()->create([
            'user_id' => $owner->id,
            'title' => 'Rex',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 2,
        ]);
    }

    public function test_quote_uses_provider_custom_price(): void
    {
        $customer = User::factory()->create(['wallet' => 5_000_000]);
        $coach = $this->makeCoach();
        ProviderServicePrice::query()->create([
            'user_id' => $coach->id,
            'service_type' => 'coach',
            'base_price' => 900000,
            'training_surcharges' => ['beginner' => 0],
        ]);

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.quote'), [
                'service_type' => 'coach',
                'fulfillment_mode' => 'direct',
                'training_type' => 'beginner',
                'provider_id' => $coach->id,
            ])
            ->assertOk()
            ->assertJsonPath('price_source', 'provider')
            ->assertJsonPath('total', 900000);
    }

    public function test_direct_booking_redirects_to_pay_and_wallet_settles(): void
    {
        $customer = User::factory()->create(['wallet' => 2_000_000]);
        $coach = $this->makeCoach();
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        ProviderServicePrice::query()->create([
            'user_id' => $coach->id,
            'service_type' => 'coach',
            'base_price' => 750000,
            'training_surcharges' => ['beginner' => 0],
        ]);

        $create = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'beginner',
                'fulfillment_mode' => 'direct',
                'provider_ids' => [$coach->id],
                'contact_name' => 'Saeed',
                'contact_phone' => '09120000000',
                'payment_method' => 'wallet',
            ])
            ->assertCreated();

        $code = $create->json('booking.booking_code');
        $this->assertTrue((bool) $create->json('payment_required'));
        $this->assertStringContainsString('/booking/pay/', (string) $create->json('success_url'));
        $this->assertSame(750000, (int) $create->json('booking.total'));

        $this->actingAs($customer)
            ->post(route('frontend.booking.pay.process', $code), [
                'payment_method' => 'wallet',
            ])
            ->assertRedirect(route('frontend.booking.success', $code));

        $customer->refresh();
        $coach->refresh();
        // Escrow: customer debited, provider NOT credited until accept.
        $this->assertSame(1_250_000, (int) $customer->wallet);
        $this->assertSame(0, (int) $coach->wallet);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'payment_status' => 'paid',
            'payment_method' => 'online',
            'gateway_driver' => 'wallet',
            'settlement_status' => 'held',
            'status' => 'PENDING',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'پرداخت رزرو موفق',
        ]);

        $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.respond', $code), ['action' => 'accept'])
            ->assertOk()
            ->assertJsonPath('booking.status', 'CONFIRMED');

        $coach->refresh();
        $this->assertSame(750000, (int) $coach->wallet);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'settlement_status' => 'settled',
            'status' => 'CONFIRMED',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'رزرو تأیید شد',
        ]);
    }

    public function test_provider_can_save_service_prices(): void
    {
        $coach = $this->makeCoach();

        $this->actingAs($coach)
            ->post(route('account.service-prices.update'), [
                'prices' => [
                    'coach' => [
                        'base_price' => 1100000,
                        'training_surcharges' => [
                            'beginner' => 0,
                            'advanced' => 150000,
                        ],
                    ],
                ],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('provider_service_prices', [
            'user_id' => $coach->id,
            'service_type' => 'coach',
            'base_price' => 1100000,
        ]);
    }

    public function test_gateway_callback_accepts_post_and_marks_paid(): void
    {
        $customer = User::factory()->create(['wallet' => 0]);
        $coach = $this->makeCoach();
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        $booking = Booking::query()->create([
            'user_id' => $customer->id,
            'provider_id' => $coach->id,
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'fulfillment_mode' => 'direct',
            'booking_code' => 'SP-POSTCB1',
            'status' => 'PENDING',
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'gateway_driver' => 'local',
            'gateway_transaction_id' => 'local-tx-1',
            'settlement_status' => 'unpaid',
            'price' => 100000,
            'total' => 100000,
            'booking_date' => now()->toDateString(),
            'contact_name' => 'Saeed',
            'contact_phone' => '09120000000',
        ]);

        $this->actingAs($customer)
            ->post(route('frontend.booking.pay.callback', $booking->booking_code), [
                'transactionId' => 'local-tx-1',
            ])
            ->assertRedirect(route('frontend.booking.success', $booking->booking_code));

        $this->assertDatabaseHas('bookings', [
            'booking_code' => $booking->booking_code,
            'payment_status' => 'paid',
            'settlement_status' => 'held',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'title' => 'پرداخت رزرو موفق',
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $coach->id,
            'title' => 'رزرو جدید پرداخت‌شده',
        ]);
    }
}
