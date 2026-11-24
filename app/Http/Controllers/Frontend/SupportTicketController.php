<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Support\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $tickets = SupportTicket::query()
            ->where('user_id', $user->id)
            ->latest('last_message_at')
            ->latest('id')
            ->get()
            ->map(fn (SupportTicket $t) => $this->ticketPayload($t));

        $unread = (int) SupportTicket::query()
            ->where('user_id', $user->id)
            ->sum('unread_for_user');

        return response()->json([
            'tickets' => $tickets,
            'unread' => $unread,
            'auth' => true,
        ]);
    }

    public function store(Request $request, SupportTicketService $service): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip'],
        ], [
            'subject.required' => 'موضوع را وارد کنید.',
            'body.required' => 'شرح مشکل را بنویسید.',
        ]);

        $ticket = $service->createTicket(
            $user,
            $data['subject'],
            $data['body'],
            $request->file('attachment')
        );

        return response()->json([
            'ticket' => $this->ticketPayload($ticket),
            'messages' => $ticket->messages->map->toChatArray()->values(),
        ], 201);
    }

    public function show(int $ticket): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $model = SupportTicket::query()
            ->whereKey($ticket)
            ->where('user_id', $user->id)
            ->firstOrFail();

        app(SupportTicketService::class)->markReadForUser($model);

        $messages = $model->messages()->with('user:id,name')->get()->map->toChatArray()->values();

        return response()->json([
            'ticket' => $this->ticketPayload($model->fresh()),
            'messages' => $messages,
        ]);
    }

    public function messages(Request $request, int $ticket): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $model = SupportTicket::query()
            ->whereKey($ticket)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $afterId = (int) $request->query('after_id', 0);
        $query = $model->messages()->orderBy('id');
        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get()->map->toChatArray()->values();
        if ($messages->isNotEmpty()) {
            app(SupportTicketService::class)->markReadForUser($model);
        }

        return response()->json([
            'messages' => $messages,
            'ticket' => $this->ticketPayload($model->fresh()),
        ]);
    }

    public function reply(Request $request, int $ticket, SupportTicketService $service): JsonResponse
    {
        $user = Auth::user();
        abort_unless($user, 401);

        $model = SupportTicket::query()
            ->whereKey($ticket)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (! $model->isOpen()) {
            throw ValidationException::withMessages(['ticket' => 'این تیکت بسته شده است.']);
        }

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip'],
        ]);

        if (blank($data['body'] ?? null) && ! $request->hasFile('attachment')) {
            throw ValidationException::withMessages(['body' => 'پیام یا پیوست لازم است.']);
        }

        $message = $service->reply($model, $user, 'user', $data['body'] ?? null, $request->file('attachment'));

        return response()->json([
            'message' => $message->toChatArray(),
            'ticket' => $this->ticketPayload($model->fresh()),
        ], 201);
    }

    public function bootstrap(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'auth' => false,
                'login_url' => route('login', ['redirect' => route('frontend.support.index')]),
                'unread' => 0,
            ]);
        }

        $unread = (int) SupportTicket::query()
            ->where('user_id', $user->id)
            ->sum('unread_for_user');

        return response()->json([
            'auth' => true,
            'user' => ['id' => $user->id, 'name' => $user->name],
            'unread' => $unread,
            'echo' => [
                'enabled' => config('broadcasting.default') === 'reverb',
                'key' => config('reverb.apps.apps.0.key') ?? env('REVERB_APP_KEY'),
            ],
        ]);
    }

    private function ticketPayload(SupportTicket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'code' => $ticket->code,
            'subject' => $ticket->subject,
            'status' => $ticket->status,
            'unread' => (int) $ticket->unread_for_user,
            'last_message_at' => optional($ticket->last_message_at)
                ->timezone(config('app.timezone', 'Asia/Tehran'))
                ->format('Y/m/d H:i'),
        ];
    }
}