<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Breed;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(): User
    {
        $role = Role::query()->firstOrCreate(['name' => 'coach']);
        $coach = User::factory()->create(['name' => 'Sara', 'family' => 'Mohammadi']);
        $coach->roles()->attach($role->id);

        return $coach;
    }

    public function test_meta_endpoint_lists_service_types(): void
    {
        $this->getJson(route('frontend.booking.api.meta'))
            ->assertOk()
            ->assertJsonPath('currency', 'IRT')
            ->assertJsonStructure(['service_types', 'training_types', 'slot_template']);
    }

    public function test_providers_endpoint_filters_by_role(): void
    {
        $coach = $this->makeCoach();
        User::factory()->create(['name' => 'NotCoach']);

        $this->getJson(route('frontend.booking.api.providers', ['service_type' => 'coach']))
            ->assertOk()
            ->assertJsonFragment(['id' => $coach->id]);
    }

    public function test_slots_mark_taken_times_unavailable(): void
    {
        $customer = User::factory()->create();
        $coach = $this->makeCoach();
        $date = now()->addDays(2)->toDateString();

        Booking::create([
            'user_id' => $customer->id,
            'service_type' => 'coach',
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

    public function test_store_requires_auth_and_persists_booking_code(): void
    {
        $coach = $this->makeCoach();
        $breed = Breed::query()->create(['name' => 'German Shepherd', 'description' => 'x']);
        $date = now()->addDays(3)->toDateString();

        $payload = [
            'service_type' => 'coach',
            'provider_id' => $coach->id,
            'breed_id' => $breed->id,
            'training_type' => 'beginner',
            'date' => $date,
            'slot' => '09:00',
            'contact_name' => 'Saeed',
            'contact_phone' => '09121234567',
            'payment_method' => 'online',
        ];

        $this->postJson(route('frontend.booking.api.store'), $payload)
            ->assertUnauthorized();

        $customer = User::factory()->create();

        $response = $this->actingAs($customer)
            ->postJson(route('frontend.booking.api.store'), $payload)
            ->assertCreated()
            ->assertJsonPath('booking.service_type', 'coach')
            ->assertJsonPath('booking.slot', '09:00');

        $code = $response->json('booking.booking_code');
        $this->assertNotEmpty($code);
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $code,
            'user_id' => $customer->id,
            'provider_id' => $coach->id,
            'training_type' => 'beginner',
        ]);

        $this->actingAs($customer)
            ->getJson(route('frontend.booking.api.show', $code))
            ->assertOk()
            ->assertJsonPath('booking.booking_code', $code);
    }

    public function test_legacy_wizard_routes_redirect_to_booking(): void
    {
        $this->get('/select-trainer-type')->assertRedirect('/booking');
        $this->get('/select-breed')->assertRedirect('/booking');
        $this->get('/select-time')->assertRedirect('/booking');
    }
}
