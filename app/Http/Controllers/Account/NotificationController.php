<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Support\AppCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('frontend.pages.account.notifications', [
            'user' => $user,
            'notifications' => $notifications,
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
            'focusId' => (int) $request->query('focus', 0),
        ]);
    }

    public function readAll(Request $request): JsonResponse|RedirectResponse
    {
        Notification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        AppCache::forgetUserHeader((int) Auth::id());

        return $this->respond($request, 'همه اعلان‌ها خوانده شد.');
    }

    public function read(Request $request, Notification $notification): JsonResponse|RedirectResponse
    {
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);
        $notification->markRead();
        AppCache::forgetUserHeader((int) Auth::id());

        return $this->respond($request, 'اعلان خوانده شد.');
    }

    private function respond(Request $request, string $message): JsonResponse|RedirectResponse
    {
        $unread = Notification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'unread' => $unread,
            ]);
        }

        return back()->with('success', $message);
    }
}
