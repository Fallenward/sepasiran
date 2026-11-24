<?php

namespace App\Support;

use App\Events\SupportMessageCreated;
use App\Events\SupportTicketOpened;
use App\Models\Notification;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupportTicketService
{
    public function createTicket(User $user, string $subject, string $body, ?UploadedFile $file = null): SupportTicket
    {
        return DB::transaction(function () use ($user, $subject, $body, $file) {
            $ticket = SupportTicket::query()->create([
                'user_id' => $user->id,
                'code' => $this->uniqueCode(),
                'subject' => $subject,
                'status' => 'open',
                'unread_for_admin' => 1,
                'unread_for_user' => 0,
                'last_message_at' => now(config('app.timezone', 'Asia/Tehran')),
            ]);

            $message = $this->storeMessage($ticket, $user, 'user', $body, $file, false);
            $ticket->setRelation('user', $user);
            $ticket->load('messages');

            $this->notifyAdmins(
                'تیکت پشتیبانی جدید',
                'تیکت '.$ticket->code.' — '.$ticket->subject
            );

            $this->safeBroadcast(new SupportTicketOpened($ticket->fresh(['user'])));
            $this->safeBroadcast(new SupportMessageCreated($message));

            return $ticket->fresh(['messages', 'user']);
        });
    }

    public function reply(SupportTicket $ticket, User $actor, string $senderType, ?string $body, ?UploadedFile $file = null): SupportTicketMessage
    {
        return DB::transaction(function () use ($ticket, $actor, $senderType, $body, $file) {
            $message = $this->storeMessage($ticket, $actor, $senderType, $body, $file, true);

            if ($senderType === 'admin') {
                $ticket->unread_for_user = (int) $ticket->unread_for_user + 1;
                $ticket->unread_for_admin = 0;
                $ticket->status = 'answered';
                Notification::query()->create([
                    'user_id' => $ticket->user_id,
                    'title' => 'پاسخ پشتیبانی',
                    'message' => 'پاسخ جدیدی برای تیکت '.$ticket->code.' ثبت شد.',
                ]);
            } else {
                $ticket->unread_for_admin = (int) $ticket->unread_for_admin + 1;
                $ticket->unread_for_user = 0;
                if ($ticket->status === 'answered') {
                    $ticket->status = 'open';
                }
                $this->notifyAdmins(
                    'پیام جدید در تیکت',
                    'تیکت '.$ticket->code.' پیام جدید دارد.'
                );
            }

            $ticket->last_message_at = now(config('app.timezone', 'Asia/Tehran'));
            $ticket->save();

            $this->safeBroadcast(new SupportMessageCreated($message->fresh(['ticket.user'])));

            return $message;
        });
    }

    public function markReadForUser(SupportTicket $ticket): void
    {
        if ((int) $ticket->unread_for_user > 0) {
            $ticket->unread_for_user = 0;
            $ticket->save();
        }
    }

    public function markReadForAdmin(SupportTicket $ticket): void
    {
        if ((int) $ticket->unread_for_admin > 0) {
            $ticket->unread_for_admin = 0;
            $ticket->save();
        }
    }

    private function storeMessage(
        SupportTicket $ticket,
        User $actor,
        string $senderType,
        ?string $body,
        ?UploadedFile $file,
        bool $persistCountsLater
    ): SupportTicketMessage {
        $path = null;
        $name = null;
        $mime = null;
        $size = null;

        if ($file) {
            $path = $file->store('support-attachments/'.$ticket->id, 'public');
            $name = $file->getClientOriginalName();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();
        }

        return SupportTicketMessage::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $actor->id,
            'sender_type' => $senderType,
            'body' => $body,
            'attachment_path' => $path,
            'attachment_name' => $name,
            'attachment_mime' => $mime,
            'attachment_size' => $size,
        ]);
    }

    private function notifyAdmins(string $title, string $message): void
    {
        $admins = User::query()
            ->where(function ($q) {
                $q->where('is_admin', true)
                    ->orWhereHas('roles', fn ($r) => $r->whereIn('name', ['admin', 'administrator']));
            })
            ->get(['id']);

        foreach ($admins as $admin) {
            Notification::query()->create([
                'user_id' => $admin->id,
                'title' => $title,
                'message' => $message,
            ]);
        }
    }

    private function uniqueCode(): string
    {
        do {
            $code = 'TK-'.Str::upper(Str::random(8));
        } while (SupportTicket::query()->where('code', $code)->exists());

        return $code;
    }

    private function safeBroadcast(object $event): void
    {
        try {
            event($event);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}