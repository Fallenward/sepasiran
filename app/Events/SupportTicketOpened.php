<?php

namespace App\Events;

use App\Models\SupportTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportTicketOpened implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public SupportTicket $ticket)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.support')];
    }

    public function broadcastAs(): string
    {
        return 'ticket.opened';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'code' => $this->ticket->code,
            'subject' => $this->ticket->subject,
            'user_name' => $this->ticket->user?->name,
            'unread_for_admin' => (int) $this->ticket->unread_for_admin,
            'url' => route('admin.support-tickets.show', $this->ticket->id),
        ];
    }
}