<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingHubController extends Controller
{
    /**
     * Unified reservation hub with both:
     * - requests the user sent (mine)
     * - requests the user received as provider (inbox)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $isProvider = $user->hasRole('coach') || $user->hasRole('clinic') || $user->hasRole('breeder');
        $tab = $request->query('tab', $isProvider ? 'inbox' : 'mine');
        if (! in_array($tab, ['mine', 'inbox'], true)) {
            $tab = 'mine';
        }
        if ($tab === 'inbox' && ! $isProvider) {
            $tab = 'mine';
        }

        $mine = Booking::query()
            ->where('user_id', $user->id)
            ->with(['provider', 'dog'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
            ->paginate(15, ['*'], 'mine_page')
            ->appends(['tab' => 'mine']);

        $inbox = collect();
        $inboxPaginator = null;
        if ($isProvider) {
            $inboxPaginator = Booking::query()
                ->where('provider_id', $user->id)
                ->with(['user', 'dog'])
                ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
                ->paginate(15, ['*'], 'inbox_page')
                ->appends(['tab' => 'inbox']);
            $inbox = $inboxPaginator;
        }

        return view('frontend.pages.account.bookings', [
            'user' => $user,
            'tab' => $tab,
            'isProvider' => $isProvider,
            'mine' => $mine,
            'inbox' => $inbox,
            'bookings' => $tab === 'inbox' ? $inbox : $mine,
            'mode' => $tab === 'inbox' ? 'inbox' : 'mine',
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => $user->dashboardRoute(),
            'mineCount' => $mine->total(),
            'inboxCount' => $inboxPaginator ? $inboxPaginator->total() : 0,
        ]);
    }

    /** @deprecated keep named routes working */
    public function mine(Request $request): View
    {
        $request->query->set('tab', 'mine');

        return $this->index($request);
    }

    /** @deprecated keep named routes working */
    public function inbox(Request $request): View
    {
        $request->query->set('tab', 'inbox');

        return $this->index($request);
    }
}
