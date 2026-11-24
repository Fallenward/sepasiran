<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Support\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportTicket::query()->with(['user:id,name,email,phone'])->latest('last_message_at')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = '%'.trim((string) $request->q).'%';
            $query->where(function ($q) use ($term) {
                $q->where('code', 'like', $term)
                    ->orWhere('subject', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        $tickets = $query->paginate(20)->appends($request->query());
        $openCount = SupportTicket::query()->whereIn('status', ['open', 'answered'])->where('unread_for_admin', '>', 0)->count();

        return view('admin.support-tickets.index', compact('tickets', 'openCount'));
    }

    public function show(int $support_ticket): View
    {
        $ticket = SupportTicket::query()->with(['user', 'messages.user'])->findOrFail($support_ticket);
        app(SupportTicketService::class)->markReadForAdmin($ticket);

        return view('admin.support-tickets.show', [
            'ticket' => $ticket->fresh(['user', 'messages.user']),
        ]);
    }

    public function reply(Request $request, int $support_ticket, SupportTicketService $service): RedirectResponse|JsonResponse
    {
        $admin = Auth::user();
        abort_unless($admin, 403);

        $ticket = SupportTicket::query()->findOrFail($support_ticket);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip'],
        ]);

        if (blank($data['body'] ?? null) && ! $request->hasFile('attachment')) {
            throw ValidationException::withMessages(['body' => 'پیام یا پیوست لازم است.']);
        }

        $message = $service->reply($ticket, $admin, 'admin', $data['body'] ?? null, $request->file('attachment'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message->toChatArray(),
                'ticket' => [
                    'id' => $ticket->id,
                    'status' => $ticket->fresh()->status,
                    'unread_for_admin' => 0,
                ],
            ], 201);
        }

        return back()->with('success', 'پاسخ ارسال شد.');
    }

    public function poll(Request $request): JsonResponse
    {
        $afterId = (int) $request->query('after_id', 0);
        $tickets = SupportTicket::query()
            ->with('user:id,name')
            ->when($afterId > 0, fn ($q) => $q->where('id', '>', $afterId))
            ->where('unread_for_admin', '>', 0)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn (SupportTicket $t) => [
                'id' => $t->id,
                'code' => $t->code,
                'subject' => $t->subject,
                'user_name' => $t->user?->name,
                'unread_for_admin' => (int) $t->unread_for_admin,
                'url' => route('admin.support-tickets.show', $t->id),
            ]);

        $unread = (int) SupportTicket::query()->sum('unread_for_admin');

        return response()->json([
            'unread' => $unread,
            'tickets' => $tickets,
        ]);
    }

    public function messages(Request $request, int $support_ticket): JsonResponse
    {
        $ticket = SupportTicket::query()->findOrFail($support_ticket);
        $afterId = (int) $request->query('after_id', 0);
        $query = $ticket->messages()->orderBy('id');
        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }
        $messages = $query->get()->map->toChatArray()->values();
        if ($messages->isNotEmpty()) {
            app(SupportTicketService::class)->markReadForAdmin($ticket);
        }

        return response()->json([
            'messages' => $messages,
            'unread' => (int) SupportTicket::query()->sum('unread_for_admin'),
        ]);
    }
}