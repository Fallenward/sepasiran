<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingOffer;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(?array $attrs = []): User
    {
        $role = Role::query()->firstOrCreate(['name' => 'coach']);
        $coach = User::factory()->create(array_merge(['name' => 'Sara', 'family' => 'Mohammadi'], $attrs));
        $coach->roles()->attach($role->id);

        return $coach;
    }

    private function makeCity(string $name = 'Shiraz'): City
    {
        $owner = User::factory()->create();

        return City::query()->forceCreate([
            'user_id' => $owner->id,
            'name' => $name,
        ]);
    }

    private function makeDog(User $owner, ?Breed $breed = null): Dog
    {
        $breed ??= Breed::query()->create(['name' => 'German Shepherd', 'description' => 'x']);

        return Dog::query()->create([
            'user_id' => $owner->id,
            'title' => 'Rocky',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 3,
        ]);
    }

    public function test_meta_endpoint_lists_v2_catalog(): void
    {
        $this->getJson(route('frontend.booking.api.meta'))
            ->assertOk()
            ->assertJsonPath('currency', 'IRT')
            ->assertJsonPath('pricing.open_listing_fee', 50000)
            ->assertJsonStructure([
                'service_types',
                'training_types',
                'fulfillment_modes',
                'slot_template',
                'statuses',
                'pricing',
                'flow',
            ]);
    }

    public function test_providers_endpoint_filters_by_role(): void
    {
        $coach = $this->makeCoach();
        User::factory()->create(['name' => 'NotCoach']);

        $this->getJson(route('frontend.booking.api.providers', ['service_type' => 'coach']))
            ->assertOk()
            ->assertJsonFragment(['id' => $coach->id]);
    }

    public function test_providers_endpoint_filters_by_city_including_duplicate_names(): void
    {
        $tehranA = $this->makeCity('تهران');
        $tehranB = $this->makeCity('تهران');
        $mashhad = $this->makeCity('مشهد');

        $inTehran = $this->makeCoach(['name' => 'TehranCoach', 'city_id' => $tehranA->id]);
        $otherCity = $this->makeCoach(['name' => 'MashhadCoach', 'city_id' => $mashhad->id]);

        // Selecting the duplicate city id must still return providers on the canonical city row.
        $this->getJson(route('frontend.booking.api.providers', [
            'service_type' => 'coach',
            'city_id' => $tehranB->id,
        ]))
            ->assertOk()
            ->assertJsonPath('city_name', 'تهران')
            ->assertJsonFragment(['id' => $inTehran->id])
            ->assertJsonMissing(['id' => $otherCity->id]);

        $this->getJson(route('frontend.booking.api.cities', ['q' => 'ته']))
            ->assertOk()
            ->assertJsonCount(1, 'results');
    }

    public function test_slots_mark_taken_times_unavailable(): void
    {
        $customer = User::factory()->create();
        $coach = $this->makeCoach();
        $date = now()->addDays(2)->toDateString();

        Booking::create([
            'user_id' => $customer->id,
            'service_type' => 'coach',
            'fulfillment_mode' => 'direct',
            'provider_id' => $coach->id,
            'appointment_date' => $date.' 11:00:00',
            'slot' => '11:00',
            'booking_date' => now()->toDateString(),
            'price' => 1000,
            'total' => 1000,
            'status' => 'PENDING',
            'payment_status' => 'pending',
            'payment_method' => 'online',
            'contact_name' => 'A',
            'contact_phone' => '0912',
        ]);

        $response = $this->getJson(route('frontend.booking.api.slots', [
            'provider_id' => $coach->id,
            'date' => $date,
        ]))->assertOk();

        $slot11 = collect($response->json('slots'))->firstWhere('time', '11:00');
        $slot9 = collect($response->json('slots'))->firstWhere('time', '09:00');

        $this->assertFalse($slot11['available']);
        $this->assertTrue($slot9['available']);
        $this->assertNotEmpty($response->json('date_jalali'));
    }

    public function test_my_dogs_requires_auth_and_lists_owned_pets(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $dog = $this->makeDog($owner);
        $this->makeDog($other);

        $this->getJson(route('frontend.booking.api.my-dogs'))->assertUnauthorized();

        $this->actingAs($owner)
            ->getJson(route('frontend.booking.api.my-dogs'))
            ->assertOk()
            ->assertJsonPath('empty', false)
            ->assertJsonFragment(['id' => $dog->id, 'title' => 'Rocky']);
    }

    public function test_quote_includes_open_listing_fee(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.quote'), [
                'service_type' => 'coach',
                'fulfillment_mode' => 'open',
                'training_type' => 'behavioral',
            ])
            ->assertOk()
            ->assertJsonPath('price', 1500000)
            ->assertJsonPath('surcharge', 300000)
            ->assertJsonPath('mode_fee', 50000)
            ->assertJsonPath('total', 1550000);
    }

    public function test_direct_store_requires_owned_dog_and_persists_providers(): void
    {
        $coach = $this->makeCoach();
        $city = $this->makeCity();
        $customer = User::factory()->create();
        $dog = $this->makeDog($customer);
        $date = now()->addDays(3)->toDateString();

        $payload = [
            'dog_id' => $dog->id,
            'city_id' => $city->id,
            'service_type' => 'coach',
            'training_type' => 'beginner',
            'fulfillment_mode' => 'direct',
            'provider_ids' => [$coach->id],
            'preferred_date' => $date,
            'preferred_slot' => '09:00',
            'contact_name' => 'Saeed',
            'contact_phone' => '09121234567',
            'payment_method' => 'online',
        ];

        $this->postJson(route('frontend.booking.api.store'), $payload)->assertUnauthorized();

        $response = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), $payload)
            ->assertCreated()
            ->assertJsonPath('booking.service_type', 'coach')
            ->assertJsonPath('booking.fulfillment_mode', 'direct')
            ->assertJsonPath('booking.status', 'PENDING')
            ->assertJsonPath('booking.dog.id', $dog->id)
            ->assertJsonPath('booking.preferred_slot', '09:00');

        $code = $response->json('booking.booking_code');
        $this->assertNotEmpty($code);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'user_id' => $customer->id,
            'dog_id' => $dog->id,
            'provider_id' => $coach->id,
            'fulfillment_mode' => 'direct',
        ]);
        $this->assertDatabaseHas('booking_providers', [
            'provider_id' => $coach->id,
        ]);
    }

    public function test_open_request_offer_accept_flow(): void
    {
        $coach = $this->makeCoach();
        $city = $this->makeCity('Tehran');
        $customer = User::factory()->create();
        $dog = $this->makeDog($customer);

        $create = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'advanced',
                'fulfillment_mode' => 'open',
                'preferred_date' => now()->addDays(5)->toDateString(),
                'preferred_slot' => '14:30',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
                'notes' => 'open please',
            ])
            ->assertCreated()
            ->assertJsonPath('booking.status', 'OPEN')
            ->assertJsonPath('booking.fulfillment_mode', 'open')
            ->assertJsonPath('booking.provider', null);

        $code = $create->json('booking.booking_code');

        $this->actingAs($coach)
            ->getJson(route('frontend.booking.api.inbox.open'))
            ->assertOk()
            ->assertJsonFragment(['booking_code' => $code]);

        $offerResponse = $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.offers.store', $code), [
                'price' => 1100000,
                'message' => 'can come tomorrow',
                'proposed_date' => now()->addDays(6)->toDateString(),
                'proposed_slot' => '11:00',
            ])
            ->assertCreated()
            ->assertJsonPath('offer.price', 1100000);

        $offerId = $offerResponse->json('offer.id');

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.offers.accept', [$code, $offerId]))
            ->assertOk()
            ->assertJsonPath('booking.status', 'ACCEPTED')
            ->assertJsonPath('booking.provider.id', $coach->id)
            ->assertJsonPath('booking.total', 1100000);

        $this->assertDatabaseHas('booking_offers', [
            'id' => $offerId,
            'status' => 'accepted',
        ]);
    }

    public function test_cancel_open_booking(): void
    {
        $city = $this->makeCity();
        $customer = User::factory()->create();
        $dog = $this->makeDog($customer);

        $code = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'clinic',
                'fulfillment_mode' => 'open',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
            ])
            ->assertCreated()
            ->json('booking.booking_code');

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.cancel', $code))
            ->assertOk()
            ->assertJsonPath('booking.status', 'CANCELLED');
    }

    public function test_legacy_wizard_routes_redirect_to_booking(): void
    {
        $this->get('/select-trainer-type')->assertRedirect('/booking');
        $this->get('/select-breed')->assertRedirect('/booking');
        $this->get('/select-time')->assertRedirect('/booking');
    }
}
