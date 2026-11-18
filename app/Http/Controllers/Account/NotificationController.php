<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
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
        ]);
    }

    public function readAll(Request $request): RedirectResponse
    {
        Notification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'همه اعلان‌ها خوانده شد.');
    }

    public function read(Notification $notification): RedirectResponse
    {
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);
        $notification->markRead();

        return back();
    }
}
