<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Support\BookingLifecycleService;
use App\Support\BookingPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingHubController extends Controller
{
    /** Once decided, status can no longer be changed from the hub. */
    private const LOCKED = ['ACCEPTED', 'CONFIRMED', 'COMPLETED', 'CANCELLED', 'REJECTED'];

    /** Hub decisions: accept, provider reject, or customer cancel. */
    private const DECISIONS = ['ACCEPTED', 'CANCELLED', 'REJECTED'];

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

        // Dashboard is for direct bookings only — open marketplace lives on the public site.
        $mine = Booking::query()
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('fulfillment_mode', 'direct')
                    ->orWhereNull('fulfillment_mode');
            })
            ->with(['provider', 'dog', 'offers'])
            ->withCount(['offers', 'messages'])
            ->orderByRaw('COALESCE(appointment_date, preferred_date, booking_date) desc')
            ->paginate(15, ['*'], 'mine_page')
            ->appends(['tab' => 'mine']);

        $inbox = collect();
        $inboxPaginator = null;
        if ($isProvider) {
            $inboxPaginator = Booking::query()
                ->where(function ($q) {
                    $q->where('fulfillment_mode', 'direct')
                        ->orWhereNull('fulfillment_mode');
                })
                ->where(function ($q) use ($user) {
                    $q->where('provider_id', $user->id)
                        ->orWhereHas('invitedProviders', fn ($iq) => $iq->where('provider_id', $user->id));
                })
                ->with(['user', 'dog'])
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

    public function show(Booking $booking): View|\Illuminate\Http\RedirectResponse
    {
        if (($booking->fulfillment_mode ?? 'direct') === 'open') {
            return redirect()->route('frontend.booking.open.show', $booking->booking_code);
        }

        $user = Auth::user();
        $isOwner = (int) $booking->user_id === (int) $user->id;
        $isProvider = (int) $booking->provider_id === (int) $user->id
            || $booking->invitedProviders()->where('provider_id', $user->id)->exists();

        abort_unless($isOwner || $isProvider || $user->hasRole('admin'), 403);

        $booking->load([
            'provider',
            'user',
            'dog.breed',
            'dog.media',
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

    /**
     * Accept, reject (provider), or cancel (customer) — no free-form status menu.
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();
        $isOwner = (int) $booking->user_id === (int) $user->id;
        $isProvider = (int) $booking->provider_id === (int) $user->id
            || $booking->invitedProviders()->where('provider_id', $user->id)->exists();
        $isAdmin = $user->hasRole('admin');

        abort_unless($isOwner || $isProvider || $isAdmin, 403);

        if (in_array(strtoupper((string) $booking->status), self::LOCKED, true)) {
            throw ValidationException::withMessages([
                'status' => 'این درخواست قبلاً تصمیم‌گیری شده و قابل تغییر نیست.',
            ]);
        }

        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(self::DECISIONS)],
            'tab' => ['nullable', 'string', Rule::in(['mine', 'inbox'])],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $decision = strtoupper($data['status']);
        $life = app(BookingLifecycleService::class);
        $pay = app(BookingPaymentService::class);

        // Owner may only cancel.
        if ($isOwner && ! $isProvider && ! $isAdmin && $decision !== 'CANCELLED') {
            throw ValidationException::withMessages([
                'status' => 'مشتری فقط می‌تواند درخواست را لغو کند؛ پذیرش از طریق پیشنهاد/ارائه‌دهنده است.',
            ]);
        }

        // Provider may accept or reject (not customer-style cancel label).
        if ($isProvider && ! $isOwner && ! $isAdmin && $decision === 'CANCELLED') {
            $decision = 'REJECTED';
        }

        if ($decision === 'ACCEPTED') {
            abort_unless($isProvider || $isAdmin, 403);
            $booking->forceFill([
                'status' => 'ACCEPTED',
                'provider_id' => $isProvider ? $user->id : $booking->provider_id,
            ])->save();
            if ($booking->fresh()->isHeld()) {
                $pay->settleHeld($booking->fresh());
            }
            $flash = 'درخواست پذیرفته شد.';
        } elseif ($decision === 'REJECTED') {
            abort_unless($isProvider || $isAdmin, 403);
            $life->reject($booking, 'reject', $data['reason'] ?? null);
            $flash = 'درخواست رد شد.';
        } else {
            // CANCELLED — customer or admin withdrawal.
            abort_unless($isOwner || $isAdmin, 403);
            $life->cancel($booking, 'cancel');
            $flash = 'درخواست لغو شد.';
        }

        $tab = $data['tab'] ?? ($isProvider && ! $isOwner ? 'inbox' : 'mine');

        return redirect()
            ->route('account.bookings.index', ['tab' => $tab])
            ->with('success', $flash);
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
