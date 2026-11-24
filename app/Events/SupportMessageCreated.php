<?php

namespace App\Events;

use App\Models\SupportTicketMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportMessageCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportTicketMessage $message)
    {
        $this->message->loadMissing('ticket.user');
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('support.ticket.'.$this->message->support_ticket_id),
        ];

        if ($this->message->sender_type === 'user') {
            $channels[] = new PrivateChannel('admin.support');
        } else {
            $channels[] = new PrivateChannel('App.Models.User.'.$this->message->ticket->user_id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->message->support_ticket_id,
            'ticket_code' => $this->message->ticket?->code,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->toChatArray(),
            'unread_for_admin' => (int) ($this->message->ticket?->unread_for_admin ?? 0),
            'unread_for_user' => (int) ($this->message->ticket?->unread_for_user ?? 0),
        ];
    }
}