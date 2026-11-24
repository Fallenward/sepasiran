<?php

namespace Tests\Feature;

use App\Models\SupportMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShelterHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_shelter_page_is_useful_hub(): void
    {
        $this->get(route('frontend.shelter.index'))
            ->assertOk()
            ->assertSee('پناهگاه سپاسیران', false)
            ->assertSee('سگ‌های قابل پذیرش', false)
            ->assertSee('کمک به پناهگاه', false)
            ->assertSee('name="intent"', false);
    }

    public function test_shelter_interest_form_persists_message(): void
    {
        $this->from(route('frontend.shelter.index'))
            ->post(route('frontend.shelter.store'), [
                'intent' => 'report',
                'name' => 'سعید',
                'email' => 'shelter@example.com',
                'phone' => '09120000000',
                'city' => 'تهران',
                'message' => 'سگ قهوه‌ای نزدیک پارک پیدا شد.',
            ])
            ->assertRedirect(route('frontend.shelter.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('support_messages', [
            'email' => 'shelter@example.com',
            'phone' => '09120000000',
        ]);

        $row = SupportMessage::query()->where('email', 'shelter@example.com')->first();
        $this->assertNotNull($row);
        $this->assertStringContainsString('گزارش سگ بی‌سرپرست', $row->message);
        $this->assertStringContainsString('تهران', $row->message);
    }

    public function test_shelter_form_validation(): void
    {
        $this->from(route('frontend.shelter.index'))
            ->post(route('frontend.shelter.store'), [])
            ->assertRedirect(route('frontend.shelter.index'))
            ->assertSessionHasErrors(['intent', 'name', 'email', 'phone', 'message']);
    }
}