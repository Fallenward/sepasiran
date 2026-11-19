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
            ->with(['provider', 'dog', 'offers'])
            ->withCount(['offers', 'messages'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
            ->paginate(15, ['*'], 'mine_page')
            ->appends(['tab' => 'mine']);

        $inbox = collect();
        $inboxPaginator = null;
        if ($isProvider) {
            $inboxPaginator = Booking::query()
                ->where(function ($q) use ($user) {
                    $q->where('provider_id', $user->id)
                        ->orWhereHas('invitedProviders', fn ($iq) => $iq->where('provider_id', $user->id))
                        ->orWhere(function ($open) use ($user) {
                            $open->where('fulfillment_mode', 'open')
                                ->where('status', 'OPEN')
                                ->whereHas('offers', fn ($oq) => $oq->where('provider_id', $user->id));
                        });
                })
                ->with(['user', 'dog', 'offers' => fn ($q) => $q->where('provider_id', $user->id)])
                ->withCount(['messages'])
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

    public function show(Booking $booking): View
    {
        $user = Auth::user();
        $isOwner = (int) $booking->user_id === (int) $user->id;
        $isProvider = (int) $booking->provider_id === (int) $user->id
            || $booking->invitedProviders()->where('provider_id', $user->id)->exists()
            || $booking->offers()->where('provider_id', $user->id)->exists();

        abort_unless($isOwner || $isProvider || $user->hasRole('admin'), 403);

        $booking->load([
            'provider',
            'user',
            'dog.breed',
            'city',
            'offers.provider',
            'invitedProviders',
            'messages.user',
        ]);

        return view('frontend.pages.account.booking-show', [
            'user' => $user,
            'booking' => $booking,
            'isOwner' => $isOwner,
            'isProvider' => $isProvider,
            'roleLabel' => $user->primaryRoleLabel(),
            'backUrl' => route('account.bookings.index', [
                'tab' => $isOwner ? 'mine' : 'inbox',
            ]),
            'lockedStatuses' => self::LOCKED,
        ]);
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        $isOwner = (int) $booking->user_id === (int) $user->id;
        $isProvider = (int) $booking->provider_id === (int) $user->id
            || $booking->invitedProviders()->where('provider_id', $user->id)->exists();

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
        if (strtoupper($data['status']) === 'ACCEPTED' && $isProvider) {
            $booking->forceFill(['provider_id' => $user->id])->save();
        }

        $tab = $data['tab'] ?? ($isProvider && ! $isOwner ? 'inbox' : 'mine');

        return redirect()
            ->route('account.bookings.index', ['tab' => $tab])
            ->with('success', 'وضعیت درخواست به‌روز شد.');
    }

    public function mine(Request $request): View
    {
        $request->query->set('tab', 'mine');

        return $this->index($request);
    }

    public function inbox(Request $request): View
    {
        $request->query->set('tab', 'inbox');

        return $this->index($request);
    }
}