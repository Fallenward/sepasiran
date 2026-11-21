<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Dog;
use App\Models\OpenSession;
use App\Models\OpenSessionApplication;
use App\Models\OpenSessionMessage;
use App\Models\OpenSessionOffer;
use App\Support\BookingCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OpenSessionController extends Controller
{
    public function index(Request $request): View
    {
        $q = OpenSession::query()
            ->with('creator:id,name,family')
            ->where('status', 'PUBLISHED')
            ->where(function ($b) {
                $b->whereNull('deadline_at')->orWhere('deadline_at', '>', now());
            })
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $q->where('category', $request->string('category'));
        }
        if ($request->filled('q')) {
            $term = '%'.$request->string('q').'%';
            $q->where(function ($b) use ($term) {
                $b->where('title', 'like', $term)->orWhere('description', 'like', $term);
            });
        }

        return view('frontend.pages.open-sessions.index', [
            'sessions' => $q->paginate(12)->withQueryString(),
        ]);
    }

    public function show(OpenSession $openSession): View
    {
        abort_unless(in_array($openSession->status, ['PUBLISHED', 'FULFILLED', 'CLOSED'], true)
            || (Auth::check() && (int) Auth::id() === (int) $openSession->user_id), 404);

        $openSession->load(['creator:id,name,family', 'applications' => fn ($q) => $q->with('user:id,name,family')]);
        $myApp = Auth::check()
            ? $openSession->applications->firstWhere('user_id', Auth::id())
            : null;

        return view('frontend.pages.open-sessions.show', [
            'session' => $openSession,
            'myApplication' => $myApp,
        ]);
    }

    public function mine(): View
    {
        abort_unless(Auth::check(), 401);
        $mine = OpenSession::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(20);

        return view('frontend.pages.open-sessions.mine', ['sessions' => $mine]);
    }

    public function create(): View
    {
        abort_unless(Auth::check(), 401);

        return view('frontend.pages.open-sessions.form', [
            'session' => new OpenSession(['status' => 'DRAFT', 'participants_needed' => 1, 'duration_minutes' => 60, 'flexible_schedule' => true]),
            'roles' => ['coach', 'clinic', 'breeder', 'customer'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::check(), 401);
        $data = $this->validated($request);
        $data['user_id'] = Auth::id();
        $data['status'] = $request->boolean('publish') ? 'PUBLISHED' : 'DRAFT';
        $session = OpenSession::query()->create($data);

        return redirect()->route('frontend.open-sessions.show', $session)->with('success', 'جلسه باز ذخیره شد.');
    }

    public function publish(OpenSession $openSession): RedirectResponse
    {
        $this->assertOwner($openSession);
        $openSession->update(['status' => 'PUBLISHED']);

        return back()->with('success', 'جلسه منتشر شد.');
    }

    public function pause(OpenSession $openSession): RedirectResponse
    {
        $this->assertOwner($openSession);
        $openSession->update(['status' => 'PAUSED']);

        return back()->with('success', 'جلسه متوقف شد.');
    }

    public function apply(Request $request, OpenSession $openSession): RedirectResponse
    {
        abort_unless(Auth::check(), 401);
        if (! $openSession->isOpenForApplications()) {
            throw ValidationException::withMessages(['session' => 'این جلسه پذیرای درخواست نیست.']);
        }
        if ((int) $openSession->user_id === (int) Auth::id()) {
            throw ValidationException::withMessages(['session' => 'نمی‌توانید روی جلسه خودتان درخواست دهید.']);
        }

        if ($openSession->required_role && method_exists(Auth::user(), 'hasRole')
            && ! Auth::user()->hasRole($openSession->required_role)
            && $openSession->required_role !== 'customer') {
            throw ValidationException::withMessages(['session' => 'نقش شما با نیاز این جلسه هم‌خوان نیست.']);
        }

        $data = $request->validate([
            'dog_id' => ['nullable', 'integer', 'exists:dogs,id'],
            'intro' => ['nullable', 'string', 'max:2000'],
            'experience' => ['nullable', 'string', 'max:2000'],
            'proposed_price' => ['nullable', 'integer', 'min:0'],
            'availability' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($data['dog_id'])) {
            $dog = Dog::query()->findOrFail($data['dog_id']);
            if ((int) $dog->user_id !== (int) Auth::id()) {
                throw ValidationException::withMessages(['dog_id' => 'سگ متعلق به شما نیست.']);
            }
        }

        $app = OpenSessionApplication::query()->updateOrCreate(
            ['open_session_id' => $openSession->id, 'user_id' => Auth::id()],
            array_merge($data, ['status' => 'PENDING'])
        );

        OpenSessionMessage::query()->create([
            'application_id' => $app->id,
            'user_id' => Auth::id(),
            'type' => 'text',
            'body' => $data['message'] ?: ($data['intro'] ?: 'درخواست همکاری ثبت شد.'),
        ]);

        if (! empty($data['proposed_price'])) {
            $offer = OpenSessionOffer::query()->create([
                'application_id' => $app->id,
                'user_id' => Auth::id(),
                'amount' => (int) $data['proposed_price'],
                'kind' => 'proposal',
                'status' => 'pending',
            ]);
            OpenSessionMessage::query()->create([
                'application_id' => $app->id,
                'user_id' => Auth::id(),
                'type' => 'proposal',
                'body' => 'پیشنهاد قیمت: '.number_format((int) $data['proposed_price']).' تومان',
                'offer_id' => $offer->id,
            ]);
            $app->update(['status' => 'NEGOTIATING']);
        }

        return redirect()->route('frontend.open-sessions.application', $app)->with('success', 'درخواست ارسال شد.');
    }

    public function application(OpenSessionApplication $application): View
    {
        abort_unless(Auth::check(), 401);
        $application->load(['session.creator', 'user', 'offers', 'messages.user', 'dog']);
        $uid = (int) Auth::id();
        abort_unless(
            $uid === (int) $application->user_id || $uid === (int) $application->session->user_id,
            403
        );

        return view('frontend.pages.open-sessions.application', [
            'application' => $application,
            'isOwner' => $uid === (int) $application->session->user_id,
        ]);
    }

    public function propose(Request $request, OpenSessionApplication $application): RedirectResponse
    {
        abort_unless(Auth::check(), 401);
        $uid = (int) Auth::id();
        abort_unless(
            $uid === (int) $application->user_id || $uid === (int) $application->session->user_id,
            403
        );
        abort_unless(in_array($application->status, ['PENDING', 'NEGOTIATING'], true), 422);

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $kind = $uid === (int) $application->session->user_id ? 'counter' : 'proposal';
        $offer = OpenSessionOffer::query()->create([
            'application_id' => $application->id,
            'user_id' => $uid,
            'amount' => (int) $data['amount'],
            'kind' => $kind,
            'status' => 'pending',
            'note' => $data['note'] ?? null,
        ]);

        OpenSessionMessage::query()->create([
            'application_id' => $application->id,
            'user_id' => $uid,
            'type' => $kind,
            'body' => ($kind === 'counter' ? 'پیشنهاد متقابل: ' : 'پیشنهاد قیمت: ').number_format((int) $data['amount']).' تومان',
            'offer_id' => $offer->id,
        ]);

        $application->update(['status' => 'NEGOTIATING']);

        return back()->with('success', 'پیشنهاد ثبت شد.');
    }

    public function acceptOffer(OpenSessionApplication $application, OpenSessionOffer $offer): RedirectResponse
    {
        abort_unless(Auth::check(), 401);
        abort_unless((int) $offer->application_id === (int) $application->id, 404);

        $session = $application->session;
        $uid = (int) Auth::id();
        // Only session creator accepts into a booking
        abort_unless($uid === (int) $session->user_id, 403);

        if (! $session->isOpenForApplications() && ! in_array((string) $application->status, ['PENDING', 'NEGOTIATING'], true)) {
            throw ValidationException::withMessages(['session' => 'ظرفیت یا وضعیت جلسه اجازه پذیرش نمی‌دهد.']);
        }

        $booking = DB::transaction(function () use ($session, $application, $offer, $uid) {
            $session = OpenSession::query()->lockForUpdate()->findOrFail($session->id);
            $application = OpenSessionApplication::query()->lockForUpdate()->findOrFail($application->id);
            $offer = OpenSessionOffer::query()->lockForUpdate()->findOrFail($offer->id);

            if ((string) $offer->status !== 'pending') {
                throw ValidationException::withMessages(['offer' => 'این پیشنهاد قابل پذیرش نیست.']);
            }
            if ((int) $session->participants_filled >= (int) $session->participants_needed) {
                throw ValidationException::withMessages(['session' => 'ظرفیت تکمیل است.']);
            }

            $offer->update(['status' => 'accepted']);
            OpenSessionOffer::query()
                ->where('application_id', $application->id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            $application->update(['status' => 'ACCEPTED']);

            // Close other apps if capacity filled after this accept
            $session->increment('participants_filled');
            $session->refresh();
            if ((int) $session->participants_filled >= (int) $session->participants_needed) {
                OpenSessionApplication::query()
                    ->where('open_session_id', $session->id)
                    ->where('id', '!=', $application->id)
                    ->whereIn('status', ['PENDING', 'NEGOTIATING'])
                    ->update(['status' => 'REJECTED']);
                $session->update(['status' => 'FULFILLED']);
            }

            $applicant = $application->user_id;
            $amount = (int) $offer->amount;
            $serviceType = $session->required_role && in_array($session->required_role, BookingCatalog::keys(), true)
                ? $session->required_role
                : 'coach';

            $booking = Booking::query()->create([
                'user_id' => $applicant,
                'dog_id' => $application->dog_id,
                'service_type' => $serviceType,
                'fulfillment_mode' => 'direct',
                'provider_id' => $session->user_id,
                'city_id' => optional(\App\Models\User::query()->find($applicant))->city_id,
                'preferred_date' => $session->preferred_date,
                'preferred_slot' => $session->preferred_slot,
                'booking_date' => now()->toDateString(),
                'price' => $amount,
                'tax' => 0,
                'discount' => 0,
                'total' => $amount,
                'payment_method' => 'online',
                'payment_status' => 'pending',
                'settlement_status' => 'none',
                'status' => 'PENDING',
                'contact_name' => optional(\App\Models\User::query()->find($applicant))->name ?: 'متقاضی',
                'contact_phone' => optional(\App\Models\User::query()->find($applicant))->phone ?: '—',
                'notes' => 'از جلسه باز #'.$session->id.' — '.$session->title,
                'open_session_id' => $session->id,
                'open_session_application_id' => $application->id,
            ]);

            $booking->invitedProviders()->sync([$session->user_id]);

            OpenSessionMessage::query()->create([
                'application_id' => $application->id,
                'user_id' => $uid,
                'type' => 'system',
                'body' => 'پیشنهاد پذیرفته شد. رزرو '.$booking->booking_code.' ایجاد شد.',
                'offer_id' => $offer->id,
            ]);

            return $booking;
        });

        return redirect()
            ->route('frontend.booking.pay', $booking->booking_code)
            ->with('success', 'رزرو ایجاد شد؛ پرداخت را کامل کنید.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['nullable', 'string', 'max:64'],
            'required_role' => ['nullable', 'string', 'max:32'],
            'required_specialty' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_online' => ['nullable', 'boolean'],
            'preferred_date' => ['nullable', 'date_format:Y-m-d'],
            'preferred_slot' => ['nullable', 'string', 'max:8'],
            'flexible_schedule' => ['nullable', 'boolean'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'participants_needed' => ['required', 'integer', 'min:1', 'max:50'],
            'budget_min' => ['nullable', 'integer', 'min:0'],
            'budget_max' => ['nullable', 'integer', 'min:0'],
            'deadline_at' => ['nullable', 'date'],
        ]) + [
            'is_online' => $request->boolean('is_online'),
            'flexible_schedule' => $request->boolean('flexible_schedule', true),
        ];
    }

    private function assertOwner(OpenSession $session): void
    {
        abort_unless(Auth::check() && (int) Auth::id() === (int) $session->user_id, 403);
    }
}