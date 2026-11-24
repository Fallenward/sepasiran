<?php

namespace Tests\Feature;

use App\Events\SupportMessageCreated;
use App\Events\SupportTicketOpened;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupportTicketingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['broadcasting.default' => 'null']);
    }

    private function customer(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'is_admin' => false,
            'name' => 'Customer',
            'email' => 'ticket-customer@example.com',
        ], $attrs));
    }

    private function admin(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'is_admin' => true,
            'name' => 'Admin',
            'email' => 'ticket-admin@example.com',
        ], $attrs));
    }

    public function test_support_page_loads(): void
    {
        $this->get(route('frontend.support.index'))
            ->assertOk()
            ->assertSee('میز پشتیبانی', false)
            ->assertSee('ورود برای ارسال تیکت', false)
            ->assertSee('موضوع سریع', false);

        $user = $this->customer();
        $this->actingAs($user)
            ->get(route('frontend.support.index'))
            ->assertOk()
            ->assertSee('data-sp-support-open', false)
            ->assertSee('میز تیکت‌ها', false)
            ->assertSee('تیکت جدید', false);
    }

    public function test_bootstrap_guest_and_auth(): void
    {
        $this->getJson(route('frontend.support.api.bootstrap'))
            ->assertOk()
            ->assertJson([
                'auth' => false,
                'unread' => 0,
            ])
            ->assertJsonStructure(['login_url']);

        $user = $this->customer();
        $this->actingAs($user)
            ->getJson(route('frontend.support.api.bootstrap'))
            ->assertOk()
            ->assertJson([
                'auth' => true,
                'unread' => 0,
            ])
            ->assertJsonPath('user.id', $user->id);
    }

    public function test_guest_cannot_create_or_list_tickets(): void
    {
        $this->getJson(route('frontend.support.api.tickets'))
            ->assertUnauthorized();

        $this->postJson(route('frontend.support.api.tickets.store'), [
            'subject' => 'x',
            'body' => 'y',
        ])->assertUnauthorized();
    }

    public function test_user_can_create_ticket_with_optional_attachment(): void
    {
        Event::fake([SupportTicketOpened::class, SupportMessageCreated::class]);
        Storage::fake('public');
        $user = $this->customer();
        $admin = $this->admin();

        $response = $this->actingAs($user)
            ->post(route('frontend.support.api.tickets.store'), [
                'subject' => 'مشکل رزرو',
                'body' => 'رزرو من تایید نشد.',
                'attachment' => UploadedFile::fake()->create('proof.pdf', 120, 'application/pdf'),
            ], ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonPath('ticket.subject', 'مشکل رزرو')
            ->assertJsonStructure(['ticket' => ['id', 'code', 'status'], 'messages']);

        $ticketId = $response->json('ticket.id');
        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticketId,
            'user_id' => $user->id,
            'status' => 'open',
            'unread_for_admin' => 1,
            'unread_for_user' => 0,
        ]);

        $this->assertDatabaseHas('support_ticket_messages', [
            'support_ticket_id' => $ticketId,
            'sender_type' => 'user',
            'body' => 'رزرو من تایید نشد.',
        ]);

        $message = SupportTicketMessage::query()->where('support_ticket_id', $ticketId)->first();
        $this->assertNotNull($message->attachment_path);
        Storage::disk('public')->assertExists($message->attachment_path);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'title' => 'تیکت پشتیبانی جدید',
        ]);

        Event::assertDispatched(SupportTicketOpened::class);
        Event::assertDispatched(SupportMessageCreated::class);
    }

    public function test_create_ticket_validation(): void
    {
        $user = $this->customer();

        $this->actingAs($user)
            ->postJson(route('frontend.support.api.tickets.store'), [
                'subject' => '',
                'body' => '',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['subject', 'body']);
    }

    public function test_user_lists_and_opens_own_ticket_only(): void
    {
        $user = $this->customer();
        $other = $this->customer(['email' => 'other@example.com', 'name' => 'Other']);
        $own = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-OWN12345',
            'subject' => 'Own ticket',
            'status' => 'open',
            'unread_for_admin' => 0,
            'unread_for_user' => 2,
            'last_message_at' => now(),
        ]);
        SupportTicketMessage::query()->create([
            'support_ticket_id' => $own->id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'body' => 'hello',
        ]);
        $foreign = SupportTicket::query()->create([
            'user_id' => $other->id,
            'code' => 'TK-OTHER123',
            'subject' => 'Other ticket',
            'status' => 'open',
            'unread_for_admin' => 0,
            'unread_for_user' => 0,
            'last_message_at' => now(),
        ]);

        $this->actingAs($user)
            ->getJson(route('frontend.support.api.tickets'))
            ->assertOk()
            ->assertJsonCount(1, 'tickets')
            ->assertJsonPath('tickets.0.code', 'TK-OWN12345')
            ->assertJsonPath('unread', 2);

        $this->actingAs($user)
            ->getJson(route('frontend.support.api.tickets.show', $own->id))
            ->assertOk()
            ->assertJsonPath('ticket.unread', 0)
            ->assertJsonCount(1, 'messages');

        $this->assertSame(0, (int) $own->fresh()->unread_for_user);

        $this->actingAs($user)
            ->getJson(route('frontend.support.api.tickets.show', $foreign->id))
            ->assertNotFound();
    }

    public function test_user_and_admin_reply_flow_updates_status_and_unread(): void
    {
        Event::fake([SupportMessageCreated::class]);
        $user = $this->customer();
        $admin = $this->admin();

        $create = $this->actingAs($user)
            ->postJson(route('frontend.support.api.tickets.store'), [
                'subject' => 'پرداخت',
                'body' => 'کیف پول کم شد.',
            ])
            ->assertCreated();

        $ticketId = $create->json('ticket.id');

        $this->actingAs($admin)
            ->post(route('admin.support-tickets.reply', $ticketId), [
                'body' => 'در حال بررسی است.',
            ])
            ->assertRedirect();

        $ticket = SupportTicket::query()->findOrFail($ticketId);
        $this->assertSame('answered', $ticket->status);
        $this->assertSame(0, (int) $ticket->unread_for_admin);
        $this->assertSame(1, (int) $ticket->unread_for_user);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'پاسخ پشتیبانی',
        ]);

        $this->actingAs($user)
            ->postJson(route('frontend.support.api.tickets.reply', $ticketId), [
                'body' => 'ممنون، منتظرم.',
            ])
            ->assertCreated()
            ->assertJsonPath('message.sender_type', 'user');

        $ticket->refresh();
        $this->assertSame('open', $ticket->status);
        $this->assertSame(1, (int) $ticket->unread_for_admin);
        $this->assertSame(0, (int) $ticket->unread_for_user);

        Event::assertDispatched(SupportMessageCreated::class);
    }

    public function test_reply_requires_body_or_attachment(): void
    {
        $user = $this->customer();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-EMPTY001',
            'subject' => 'Empty reply',
            'status' => 'open',
            'unread_for_admin' => 0,
            'unread_for_user' => 0,
            'last_message_at' => now(),
        ]);

        $this->actingAs($user)
            ->postJson(route('frontend.support.api.tickets.reply', $ticket->id), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    public function test_admin_can_change_ticket_status(): void
    {
        $user = $this->customer();
        $admin = $this->admin();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-STATUS01',
            'subject' => 'Status change',
            'status' => 'open',
            'unread_for_admin' => 0,
            'unread_for_user' => 0,
            'last_message_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.support-tickets.show', $ticket->id))
            ->assertOk()
            ->assertSee('تغییر وضعیت', false)
            ->assertSee('name="status"', false);

        $this->actingAs($admin)
            ->from(route('admin.support-tickets.show', $ticket->id))
            ->patch(route('admin.support-tickets.status', $ticket->id), ['status' => 'closed'])
            ->assertRedirect(route('admin.support-tickets.show', $ticket->id))
            ->assertSessionHas('success');

        $this->assertSame('closed', $ticket->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('admin.support-tickets.status', $ticket->id), ['status' => 'answered'])
            ->assertRedirect();

        $this->assertSame('answered', $ticket->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('admin.support-tickets.status', $ticket->id), ['status' => 'nope'])
            ->assertSessionHasErrors('status');
    }

    public function test_admin_index_poll_show_and_messages_poll(): void
    {
        $user = $this->customer();
        $admin = $this->admin();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-ADMIN001',
            'subject' => 'Need help',
            'status' => 'open',
            'unread_for_admin' => 3,
            'unread_for_user' => 0,
            'last_message_at' => now(),
        ]);
        $msg = SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'body' => 'first',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.support-tickets.index'))
            ->assertOk()
            ->assertSee('TK-ADMIN001', false)
            ->assertSee('Need help', false);

        $this->actingAs($admin)
            ->getJson(route('admin.support-tickets.poll'))
            ->assertOk()
            ->assertJsonPath('unread', 3)
            ->assertJsonFragment(['code' => 'TK-ADMIN001']);

        $this->actingAs($admin)
            ->get(route('admin.support-tickets.show', $ticket->id))
            ->assertOk()
            ->assertSee('first', false);

        $this->assertSame(0, (int) $ticket->fresh()->unread_for_admin);

        SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'body' => 'second',
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.support-tickets.messages', [
                'support_ticket' => $ticket->id,
                'after_id' => $msg->id,
            ]))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'second');
    }

    public function test_non_admin_cannot_access_admin_ticket_pages(): void
    {
        $user = $this->customer();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-DENY0001',
            'subject' => 'Denied',
            'status' => 'open',
            'unread_for_admin' => 1,
            'unread_for_user' => 0,
            'last_message_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.support-tickets.index'))
            ->assertRedirect(route('login'));

        $this->actingAs($user)
            ->get(route('admin.support-tickets.show', $ticket->id))
            ->assertRedirect(route('login'));
    }

    public function test_user_messages_poll_after_id(): void
    {
        $user = $this->customer();
        $ticket = SupportTicket::query()->create([
            'user_id' => $user->id,
            'code' => 'TK-POLL0001',
            'subject' => 'Poll',
            'status' => 'answered',
            'unread_for_admin' => 0,
            'unread_for_user' => 1,
            'last_message_at' => now(),
        ]);
        $first = SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'sender_type' => 'user',
            'body' => 'one',
        ]);
        SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => null,
            'sender_type' => 'admin',
            'body' => 'two',
        ]);

        $this->actingAs($user)
            ->getJson(route('frontend.support.api.tickets.messages', [
                'ticket' => $ticket->id,
                'after_id' => $first->id,
            ]))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'two')
            ->assertJsonPath('ticket.unread', 0);
    }

    public function test_home_includes_floating_support_widget_boot(): void
    {
        $this->get(route('frontend.home'))
            ->assertOk()
            ->assertSee('id="sp-support-root"', false)
            ->assertSee('bootstrapUrl', false)
            ->assertSee('ticketsUrl', false);
    }
}