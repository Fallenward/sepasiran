<?php

namespace Tests\Feature;

use App\Models\OpenSession;
use App\Models\OpenSessionApplication;
use App\Models\OpenSessionOffer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenSessionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_publish_apply_propose_accept_creates_booking(): void
    {
        $creator = User::factory()->create(['name' => 'Creator']);
        $coachRole = Role::query()->firstOrCreate(['name' => 'coach']);
        $creator->roles()->attach($coachRole->id);

        $applicant = User::factory()->create(['name' => 'Applicant', 'wallet' => 5_000_000]);

        $this->actingAs($creator)
            ->post(route('frontend.open-sessions.store'), [
                'title' => 'گروه آموزش صبح',
                'description' => 'توضیح',
                'required_role' => 'customer',
                'duration_minutes' => 60,
                'participants_needed' => 1,
                'publish' => 1,
                'flexible_schedule' => 1,
            ])
            ->assertRedirect();

        $session = OpenSession::query()->first();
        $this->assertSame('PUBLISHED', $session->status);

        $this->actingAs($applicant)
            ->post(route('frontend.open-sessions.apply', $session), [
                'intro' => 'سلام',
                'proposed_price' => 800000,
                'message' => 'آماده همکاری',
            ])
            ->assertRedirect();

        $app = OpenSessionApplication::query()->first();
        $this->assertNotNull($app);
        $offer = OpenSessionOffer::query()->where('application_id', $app->id)->first();
        $this->assertNotNull($offer);

        $this->actingAs($creator)
            ->post(route('frontend.open-sessions.accept-offer', [$app, $offer]))
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'open_session_id' => $session->id,
            'open_session_application_id' => $app->id,
            'user_id' => $applicant->id,
            'provider_id' => $creator->id,
            'total' => 800000,
            'status' => 'PENDING',
            'payment_status' => 'pending',
        ]);

        $session->refresh();
        $this->assertSame('FULFILLED', $session->status);
        $this->assertSame(1, (int) $session->participants_filled);
    }

    public function test_draft_not_listed_publicly(): void
    {
        $user = User::factory()->create();
        OpenSession::query()->create([
            'user_id' => $user->id,
            'title' => 'مخفی',
            'duration_minutes' => 60,
            'participants_needed' => 1,
            'status' => 'DRAFT',
        ]);

        $this->get(route('frontend.open-sessions.index'))
            ->assertOk()
            ->assertDontSee('مخفی');
    }
}