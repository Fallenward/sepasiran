<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\BookingOffer;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoleUser(string $role, array $attrs = []): User
    {
        Role::query()->firstOrCreate(['name' => $role]);
        $user = User::factory()->create($attrs);
        $user->roles()->attach(Role::query()->where('name', $role)->first()->id);

        return $user->fresh();
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
        $breed = Breed::query()->firstOrCreate(
            ['name' => 'Husky'],
            ['description' => 'x']
        );

        return Dog::query()->create([
            'user_id' => $owner->id,
            'title' => 'Rocky',
            'breed_id' => $breed->id,
            'status' => 'owned',
            'age' => 12,
        ]);
    }

    private function createDirectBooking(User $customer, User $coach, City $city, Dog $dog): array
    {
        $response = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'beginner',
                'fulfillment_mode' => 'direct',
                'provider_ids' => [$coach->id],
                'preferred_date' => now()->addDays(3)->toDateString(),
                'preferred_slot' => '09:00',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
            ])
            ->assertCreated()
            ->assertJsonPath('booking.status', 'PENDING')
            ->assertJsonPath('booking.fulfillment_mode', 'direct')
            ->assertJsonPath('booking.provider.id', $coach->id);

        return [$response->json('booking.booking_code'), $response->json('booking.id')];
    }

    public function test_direct_booking_provider_can_discuss_then_accept(): void
    {
        $customer = $this->makeRoleUser('user', ['name' => 'Customer']);
        $coach = $this->makeRoleUser('coach', ['name' => 'Coach']);
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        [$code] = $this->createDirectBooking($customer, $coach, $city, $dog);

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'سلام، سگ من کمی مضطرب است.',
            ])
            ->assertCreated();

        $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'باشه، می‌تونم کمک کنم.',
            ])
            ->assertCreated();

        $this->actingAs($coach)
            ->getJson(route('frontend.booking.api.messages.index', $code))
            ->assertOk()
            ->assertJsonCount(2, 'results');

        $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.respond', $code), ['action' => 'accept'])
            ->assertOk()
            ->assertJsonPath('booking.status', 'ACCEPTED')
            ->assertJsonPath('booking.provider.id', $coach->id);

        $this->assertSame(2, BookingMessage::query()->count());
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'ACCEPTED',
            'provider_id' => $coach->id,
        ]);

        $booking = Booking::query()->where('booking_code', $code)->first();
        $this->actingAs($coach)
            ->get(route('account.bookings.show', $booking))
            ->assertOk()
            ->assertSee('گفتگو', false)
            ->assertSee('سلام، سگ من کمی مضطرب است.', false);
    }

    public function test_direct_booking_provider_can_reject(): void
    {
        $customer = $this->makeRoleUser('user');
        $coach = $this->makeRoleUser('coach');
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        [$code] = $this->createDirectBooking($customer, $coach, $city, $dog);

        $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.respond', $code), ['action' => 'reject'])
            ->assertOk()
            ->assertJsonPath('booking.status', 'CANCELLED');
    }

    public function test_open_bookings_live_on_public_board_not_dashboard(): void
    {
        $customer = $this->makeRoleUser('user');
        $coach = $this->makeRoleUser('coach');
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        $code = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'beginner',
                'fulfillment_mode' => 'open',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
            ])
            ->assertCreated()
            ->json('booking.booking_code');

        $booking = Booking::query()->where('booking_code', $code)->firstOrFail();

        $this->get(route('frontend.booking.open'))
            ->assertOk()
            ->assertSee($code, false);

        $this->actingAs($coach)
            ->get(route('frontend.booking.open.show', $code))
            ->assertOk()
            ->assertSee('ارسال پیشنهاد', false)
            ->assertSee('گفتگو', false)
            ->assertSee('جزئیات رزرو', false)
            ->assertSee('جزئیات سگ', false)
            ->assertSee($code, false);

        $this->actingAs($coach)
            ->get(route('account.bookings.index', ['tab' => 'inbox']))
            ->assertOk()
            ->assertDontSee($code, false);

        $this->actingAs($customer)
            ->get(route('account.bookings.index', ['tab' => 'mine']))
            ->assertOk()
            ->assertDontSee($code, false);

        $this->actingAs($coach)
            ->get(route('account.bookings.show', $booking))
            ->assertRedirect(route('frontend.booking.open.show', $code));
    }

    public function test_open_booking_offer_chat_accept_and_reject_offer(): void
    {
        $customer = $this->makeRoleUser('user');
        $coachA = $this->makeRoleUser('coach', ['name' => 'CoachA']);
        $coachB = $this->makeRoleUser('coach', ['name' => 'CoachB']);
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        $code = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'advanced',
                'fulfillment_mode' => 'open',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
                'notes' => 'open request',
            ])
            ->assertCreated()
            ->assertJsonPath('booking.status', 'OPEN')
            ->json('booking.booking_code');

        $this->actingAs($coachA)
            ->getJson(route('frontend.booking.api.inbox.open'))
            ->assertOk()
            ->assertJsonFragment(['booking_code' => $code]);

        $offerA = $this->actingAs($coachA)
            ->postJson(route('frontend.booking.api.offers.store', $code), [
                'price' => 900000,
                'message' => 'پیشنهاد الف',
            ])
            ->assertCreated()
            ->json('offer.id');

        $offerB = $this->actingAs($coachB)
            ->postJson(route('frontend.booking.api.offers.store', $code), [
                'price' => 950000,
                'message' => 'پیشنهاد ب',
            ])
            ->assertCreated()
            ->json('offer.id');

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'در مورد زمان صحبت کنیم',
                'provider_id' => $coachA->id,
                'booking_offer_id' => $offerA,
            ])
            ->assertCreated();

        $this->actingAs($coachA)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'فردا ساعت ۱۱ خوبه',
                'booking_offer_id' => $offerA,
            ])
            ->assertCreated();

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.offers.reject', [$code, $offerB]))
            ->assertOk()
            ->assertJsonPath('offer.status', 'rejected');

        $this->assertDatabaseHas('booking_offers', [
            'id' => $offerB,
            'status' => 'rejected',
        ]);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'status' => 'OPEN',
        ]);

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.offers.accept', [$code, $offerA]))
            ->assertOk()
            ->assertJsonPath('booking.status', 'ACCEPTED')
            ->assertJsonPath('booking.provider.id', $coachA->id)
            ->assertJsonPath('booking.total', 900000);

        $this->assertDatabaseHas('booking_offers', [
            'id' => $offerA,
            'status' => 'accepted',
        ]);
    }

    public function test_stranger_cannot_chat_or_respond(): void
    {
        $customer = $this->makeRoleUser('user');
        $coach = $this->makeRoleUser('coach');
        $stranger = $this->makeRoleUser('coach', ['name' => 'Other']);
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        [$code] = $this->createDirectBooking($customer, $coach, $city, $dog);

        $this->actingAs($stranger)
            ->postJson(route('frontend.booking.api.messages.store', $code), ['body' => 'hi'])
            ->assertForbidden();

        $this->actingAs($stranger)
            ->postJson(route('frontend.booking.api.respond', $code), ['action' => 'accept'])
            ->assertForbidden();
    }

    public function test_open_chat_is_private_per_provider_thread(): void
    {
        $customer = $this->makeRoleUser('user');
        $coachA = $this->makeRoleUser('coach', ['name' => 'Alpha']);
        $coachB = $this->makeRoleUser('coach', ['name' => 'Beta']);
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        $code = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'beginner',
                'fulfillment_mode' => 'open',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
            ])
            ->assertCreated()
            ->json('booking.booking_code');

        $this->actingAs($coachA)
            ->postJson(route('frontend.booking.api.offers.store', $code), [
                'price' => 800000,
                'message' => 'A',
            ])
            ->assertCreated();

        $this->actingAs($coachB)
            ->postJson(route('frontend.booking.api.offers.store', $code), [
                'price' => 900000,
                'message' => 'B',
            ])
            ->assertCreated();

        // Provider cannot message before the reserver starts the thread.
        $this->actingAs($coachA)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'provider first should fail',
            ])
            ->assertStatus(422);

        $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'secret for A only',
                'provider_id' => $coachA->id,
            ])
            ->assertCreated()
            ->assertJsonPath('message.provider_id', $coachA->id);

        $this->actingAs($coachA)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'A can reply now',
            ])
            ->assertCreated();

        $this->actingAs($coachA)
            ->getJson(route('frontend.booking.api.messages.index', $code))
            ->assertOk()
            ->assertJsonCount(2, 'results')
            ->assertJsonPath('can_reply', true);

        $this->actingAs($coachB)
            ->getJson(route('frontend.booking.api.messages.index', $code))
            ->assertOk()
            ->assertJsonCount(0, 'results')
            ->assertJsonPath('can_reply', false);

        $this->actingAs($customer)
            ->getJson(route('frontend.booking.api.messages.index', ['booking_code' => $code, 'provider_id' => $coachB->id]))
            ->assertOk()
            ->assertJsonCount(0, 'results');

        $this->actingAs($customer)
            ->getJson(route('frontend.booking.api.threads', $code))
            ->assertOk()
            ->assertJsonFragment(['provider_id' => $coachA->id])
            ->assertJsonFragment(['provider_id' => $coachB->id]);
    }

    public function test_provider_without_offer_cannot_chat_on_open_booking(): void
    {
        $customer = $this->makeRoleUser('user');
        $coach = $this->makeRoleUser('coach');
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);

        $code = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), [
                'dog_id' => $dog->id,
                'city_id' => $city->id,
                'service_type' => 'coach',
                'training_type' => 'beginner',
                'fulfillment_mode' => 'open',
                'contact_name' => 'Saeed',
                'contact_phone' => '09121234567',
            ])
            ->assertCreated()
            ->json('booking.booking_code');

        $this->actingAs($coach)
            ->postJson(route('frontend.booking.api.messages.store', $code), [
                'body' => 'no offer yet',
            ])
            ->assertForbidden();
    }

    public function test_account_booking_hub_links_to_discussion_page(): void
    {
        $customer = $this->makeRoleUser('user');
        $coach = $this->makeRoleUser('coach');
        $city = $this->makeCity();
        $dog = $this->makeDog($customer);
        [, $id] = $this->createDirectBooking($customer, $coach, $city, $dog);
        $booking = Booking::query()->findOrFail($id);

        $this->actingAs($customer)
            ->get(route('account.bookings.index', ['tab' => 'mine']))
            ->assertOk()
            ->assertSee('گفتگو / تصمیم', false)
            ->assertSee(route('account.bookings.show', $booking, false), false);

        $this->actingAs($coach)
            ->get(route('account.bookings.index', ['tab' => 'inbox']))
            ->assertOk()
            ->assertSee('گفتگو / تصمیم', false);
    }
}