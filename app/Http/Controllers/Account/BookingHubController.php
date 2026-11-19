<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingHubController extends Controller
{
    private const LOCKED = ['ACCEPTED', 'CONFIRMED', 'COMPLETED'];

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
            'lockedStatuses' => self::LOCKED,
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        $isOwner = (int) $booking->user_id === (int) $user->id;
        $isProvider = (int) $booking->provider_id === (int) $user->id;

        abort_unless($isOwner || $isProvider, 403);

        if (in_array(strtoupper((string) $booking->status), self::LOCKED, true)) {
            throw ValidationException::withMessages([
                'status' => 'وضعیت این درخواست پس از پذیرش قابل تغییر نیست.',
            ]);
        }

        $allowed = $isProvider
            ? ['PENDING', 'OPEN', 'QUOTED', 'ACCEPTED', 'CANCELLED']
            : ['PENDING', 'OPEN', 'CANCELLED'];

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in($allowed)],
            'tab' => ['nullable', 'string', Rule::in(['mine', 'inbox'])],
        ]);

        $booking->forceFill(['status' => strtoupper($data['status'])])->save();

        $tab = $data['tab'] ?? ($isProvider && ! $isOwner ? 'inbox' : 'mine');

        return redirect()
            ->route('account.bookings.index', ['tab' => $tab])
            ->with('success', 'وضعیت درخواست به‌روز شد.');
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
