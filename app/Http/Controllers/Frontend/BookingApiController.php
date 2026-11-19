<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\BookingOffer;
use App\Models\Breed;
use App\Models\City;
use App\Models\Dog;
use App\Models\User;
use App\Support\BookingCatalog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Morilog\Jalali\Jalalian;

class BookingApiController extends Controller
{
    public function meta(): JsonResponse
    {
        return response()->json([
            'service_types' => BookingCatalog::serviceTypes(),
            'fulfillment_modes' => BookingCatalog::fulfillmentModes(),
            'training_types' => BookingCatalog::trainingTypes(),
            'slot_template' => BookingCatalog::slotTemplate(),
            'statuses' => BookingCatalog::statuses(),
            'pricing' => BookingCatalog::pricingMeta(),
            'add_dog_url' => BookingCatalog::addDogUrl(),
            'currency' => 'IRT',
            'flow' => ['dog', 'city', 'service', 'mode', 'details', 'confirm'],
        ]);
    }

    public function breeds(Request $request): JsonResponse
    {
        $term = trim((string) $request->get('q', ''));
        $rows = Breed::query()
            ->when($term !== '', fn ($q) => $q->where('name', 'like', '%'.$term.'%'))
            ->orderBy('name')
            ->limit(30)
            ->get(['id', 'name'])
            ->map(fn (Breed $b) => ['id' => $b->id, 'name' => $b->name]);

        return response()->json(['results' => $rows]);
    }

    public function cities(Request $request): JsonResponse
    {
        $term = trim((string) $request->get('q', ''));
        $rows = City::query()
            ->when($term !== '', fn ($q) => $q->where('name', 'like', '%'.$term.'%'))
            ->orderBy('name')
            ->limit(40)
            ->get(['id', 'name'])
            ->map(fn (City $c) => ['id' => $c->id, 'name' => $c->name]);

        return response()->json(['results' => $rows]);
    }

    public function myDogs(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $rows = Dog::query()
            ->with('breed:id,name')
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn (Dog $dog) => $this->serializeDog($dog));

        return response()->json([
            'results' => $rows,
            'empty' => $rows->isEmpty(),
            'add_dog_url' => BookingCatalog::addDogUrl(),
        ]);
    }

    public function storeMyDog(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'breed_id' => ['nullable', 'integer', 'exists:breeds,id'],
            'age' => ['nullable', 'integer', 'min:0', 'max:40'],
            'color' => ['nullable', 'string', 'max:60'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        $dog = Dog::query()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'breed_id' => $validated['breed_id'] ?? null,
            'age' => $validated['age'] ?? null,
            'color' => $validated['color'] ?? null,
            'image' => $validated['image'] ?? null,
            'status' => 'owned',
        ])->load('breed:id,name');

        return response()->json([
            'dog' => $this->serializeDog($dog),
            'add_dog_url' => BookingCatalog::addDogUrl(),
        ], 201);
    }

    public function listOffers(string $bookingCode): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['offers.provider'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if (!$this->canView($booking)) {
            abort(403);
        }

        return response()->json([
            'results' => $booking->offers->map(fn (BookingOffer $o) => $this->serializeOffer($o))->values(),
        ]);
    }

    public function providers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_type' => ['required', Rule::in(BookingCatalog::keys())],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'breed_id' => ['nullable', 'integer', 'exists:breeds,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $role = BookingCatalog::roleFor($validated['service_type']);
        $term = trim((string) ($validated['q'] ?? ''));

        $query = User::query()
            ->select('users.*')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', $role)
            ->with('city:id,name')
            ->orderBy('users.name');

        if (!empty($validated['city_id'])) {
            $query->where('users.city_id', $validated['city_id']);
        }

        if ($term !== '') {
            $like = '%'.$term.'%';
            $query->where(function ($builder) use ($like) {
                $builder->where('users.name', 'like', $like)
                    ->orWhere('users.family', 'like', $like);
            });
        }

        $results = $query->limit(40)->get()->map(function (User $user) use ($role) {
            $display = trim(($user->name ?? '').' '.($user->family ?? ''));

            return [
                'id' => $user->id,
                'name' => $user->name,
                'family' => $user->family,
                'display_name' => $display !== '' ? $display : ($user->name ?? 'بدون نام'),
                'city' => $user->city ? ['id' => $user->city->id, 'name' => $user->city->name] : null,
                'photo' => $user->profile_picture ?? null,
                'role' => $role,
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function slots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'service_type' => ['nullable', Rule::in(BookingCatalog::keys())],
        ]);

        $date = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();
        $taken = Booking::query()
            ->where('provider_id', $validated['provider_id'])
            ->whereDate('appointment_date', $date->toDateString())
            ->where('status', '!=', 'CANCELLED')
            ->get(['appointment_date', 'slot'])
            ->map(function (Booking $b) {
                return $b->slot ?: optional($b->appointment_date)->format('H:i');
            })
            ->filter()
            ->values()
            ->all();

        $slots = collect(BookingCatalog::slotTemplate())->map(fn (string $time) => [
            'time' => $time,
            'available' => !in_array($time, $taken, true),
        ]);

        return response()->json([
            'date' => $date->toDateString(),
            'date_jalali' => Jalalian::fromCarbon($date)->format('Y/m/d'),
            'slots' => $slots,
        ]);
    }

    public function quote(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'service_type' => ['required', Rule::in(BookingCatalog::keys())],
            'fulfillment_mode' => ['required', Rule::in(BookingCatalog::fulfillmentKeys())],
            'training_type' => ['nullable', Rule::in(BookingCatalog::trainingKeys())],
            'discount' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validated['service_type'] === 'coach' && empty($validated['training_type'])) {
            throw ValidationException::withMessages([
                'training_type' => 'برای رزرو مربی، نوع آموزش الزامی است.',
            ]);
        }

        return response()->json(
            BookingCatalog::quote(
                $validated['service_type'],
                $validated['fulfillment_mode'],
                $validated['training_type'] ?? null,
                (int) ($validated['discount'] ?? 0),
            )
        );
    }

    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'dog_id' => ['required', 'integer', 'exists:dogs,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'service_type' => ['required', Rule::in(BookingCatalog::keys())],
            'fulfillment_mode' => ['required', Rule::in(BookingCatalog::fulfillmentKeys())],
            'training_type' => ['nullable', Rule::in(BookingCatalog::trainingKeys())],
            'provider_ids' => ['nullable', 'array'],
            'provider_ids.*' => ['integer', 'exists:users,id'],
            'provider_id' => ['nullable', 'integer', 'exists:users,id'],
            'preferred_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'preferred_slot' => ['nullable', Rule::in(BookingCatalog::slotTemplate())],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'slot' => ['nullable', Rule::in(BookingCatalog::slotTemplate())],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', Rule::in(['online', 'offline'])],
        ]);

        $dog = Dog::query()->findOrFail($validated['dog_id']);
        if ((int) $dog->user_id !== (int) Auth::id()) {
            throw ValidationException::withMessages([
                'dog_id' => 'سگ انتخاب‌شده متعلق به شما نیست.',
            ]);
        }

        if ($validated['service_type'] === 'coach' && empty($validated['training_type'])) {
            throw ValidationException::withMessages([
                'training_type' => 'برای رزرو مربی، نوع آموزش الزامی است.',
            ]);
        }

        $mode = $validated['fulfillment_mode'];
        $providerIds = collect($validated['provider_ids'] ?? [])
            ->when(!empty($validated['provider_id']), fn ($c) => $c->push((int) $validated['provider_id']))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($mode === 'direct') {
            if (count($providerIds) < 1) {
                throw ValidationException::withMessages([
                    'provider_ids' => 'در حالت مستقیم حداقل یک ارائه‌دهنده لازم است.',
                ]);
            }
            foreach ($providerIds as $providerId) {
                $this->assertProviderRole($providerId, $validated['service_type']);
            }
        } else {
            $providerIds = [];
        }

        $preferredDate = $validated['preferred_date'] ?? $validated['date'] ?? null;
        $preferredSlot = $validated['preferred_slot'] ?? $validated['slot'] ?? null;

        $appointment = null;
        $slot = null;
        if ($mode === 'direct' && $preferredDate && $preferredSlot) {
            $appointment = Carbon::createFromFormat('Y-m-d H:i', $preferredDate.' '.$preferredSlot);
            $slot = $preferredSlot;
            $primary = $providerIds[0];
            if ($this->slotTaken($primary, $appointment, $slot)) {
                throw ValidationException::withMessages([
                    'preferred_slot' => 'این بازه زمانی دیگر در دسترس نیست.',
                ]);
            }
        }

        $quote = BookingCatalog::quote(
            $validated['service_type'],
            $mode,
            $validated['training_type'] ?? null,
        );

        $booking = DB::transaction(function () use ($validated, $dog, $mode, $providerIds, $preferredDate, $preferredSlot, $appointment, $slot, $quote) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'dog_id' => $dog->id,
                'service_type' => $validated['service_type'],
                'fulfillment_mode' => $mode,
                'provider_id' => $mode === 'direct' ? ($providerIds[0] ?? null) : null,
                'city_id' => $validated['city_id'],
                'breed_id' => $dog->breed_id,
                'training_type' => $validated['training_type'] ?? null,
                'appointment_date' => $appointment,
                'slot' => $slot,
                'preferred_date' => $preferredDate,
                'preferred_slot' => $preferredSlot,
                'booking_date' => now()->toDateString(),
                'price' => $quote['price'],
                'tax' => $quote['tax'],
                'discount' => $quote['discount'],
                'total' => $quote['total'],
                'payment_method' => $validated['payment_method'] ?? 'online',
                'payment_status' => 'pending',
                'status' => $mode === 'open' ? 'OPEN' : 'PENDING',
                'notes' => $validated['notes'] ?? null,
                'contact_name' => $validated['contact_name'],
                'contact_phone' => $validated['contact_phone'],
            ]);

            if ($mode === 'direct' && $providerIds !== []) {
                $booking->invitedProviders()->sync($providerIds);
            }

            return $booking;
        });

        $booking->load(['provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders']);

        return response()->json([
            'booking' => $this->serialize($booking),
            'success_url' => route('frontend.booking.success', $booking->booking_code, false),
        ], 201);
    }

    public function show(string $bookingCode): JsonResponse
    {
        $booking = Booking::query()
            ->with(['provider', 'breed', 'city', 'user', 'dog.breed', 'offers.provider', 'invitedProviders'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if (!$this->canView($booking)) {
            abort(403);
        }

        return response()->json(['booking' => $this->serialize($booking)]);
    }

    public function openInbox(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();
        $roles = $user->roles()->pluck('name')->all();
        $serviceTypes = collect(BookingCatalog::serviceTypes())
            ->filter(fn (array $row) => in_array($row['role'], $roles, true))
            ->pluck('key')
            ->values()
            ->all();

        if ($serviceTypes === []) {
            return response()->json(['results' => []]);
        }

        $query = Booking::query()
            ->with(['dog.breed', 'city', 'user'])
            ->where('status', 'OPEN')
            ->where('fulfillment_mode', 'open')
            ->whereIn('service_type', $serviceTypes)
            ->orderByDesc('id');

        if ($request->boolean('same_city') && $user->city_id) {
            $query->where('city_id', $user->city_id);
        }

        $results = $query->limit(40)->get()->map(fn (Booking $b) => $this->serialize($b));

        return response()->json(['results' => $results]);
    }

    public function storeOffer(Request $request, string $bookingCode): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()->where('booking_code', $bookingCode)->firstOrFail();
        if ($booking->status !== 'OPEN' || $booking->fulfillment_mode !== 'open') {
            throw ValidationException::withMessages([
                'booking' => 'فقط درخواست‌های باز می‌توانند پیشنهاد دریافت کنند.',
            ]);
        }

        $this->assertProviderRole((int) Auth::id(), $booking->service_type);

        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:1000'],
            'proposed_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'proposed_slot' => ['nullable', Rule::in(BookingCatalog::slotTemplate())],
        ]);

        $existing = BookingOffer::query()
            ->where('booking_id', $booking->id)
            ->where('provider_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            $existing->update([
                'price' => $validated['price'],
                'message' => $validated['message'] ?? null,
                'proposed_date' => $validated['proposed_date'] ?? null,
                'proposed_slot' => $validated['proposed_slot'] ?? null,
            ]);
            $offer = $existing->fresh('provider');
        } else {
            $offer = BookingOffer::create([
                'booking_id' => $booking->id,
                'provider_id' => Auth::id(),
                'price' => $validated['price'],
                'message' => $validated['message'] ?? null,
                'proposed_date' => $validated['proposed_date'] ?? null,
                'proposed_slot' => $validated['proposed_slot'] ?? null,
                'status' => 'pending',
            ])->load('provider');
        }

        return response()->json(['offer' => $this->serializeOffer($offer)], 201);
    }

    public function acceptOffer(string $bookingCode, int $offerId): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['offers', 'dog.breed', 'city', 'provider'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ((int) $booking->user_id !== (int) Auth::id() && !$this->isAdmin(Auth::user())) {
            abort(403);
        }

        if ($booking->status !== 'OPEN') {
            throw ValidationException::withMessages([
                'booking' => 'این درخواست دیگر باز نیست.',
            ]);
        }

        $offer = $booking->offers->firstWhere('id', $offerId);
        if (!$offer || $offer->status !== 'pending') {
            throw ValidationException::withMessages([
                'offer_id' => 'پیشنهاد معتبر نیست.',
            ]);
        }

        DB::transaction(function () use ($booking, $offer) {
            BookingOffer::query()
                ->where('booking_id', $booking->id)
                ->where('id', '!=', $offer->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            $offer->update(['status' => 'accepted']);

            $appointment = null;
            $slot = $offer->proposed_slot;
            if ($offer->proposed_date && $offer->proposed_slot) {
                $appointment = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $offer->proposed_date->format('Y-m-d').' '.$offer->proposed_slot
                );
            }

            $booking->update([
                'provider_id' => $offer->provider_id,
                'status' => 'ACCEPTED',
                'price' => $offer->price,
                'tax' => 0,
                'total' => $offer->price,
                'appointment_date' => $appointment ?? $booking->appointment_date,
                'slot' => $slot ?? $booking->slot,
                'preferred_date' => $offer->proposed_date?->format('Y-m-d') ?? $booking->preferred_date,
                'preferred_slot' => $slot ?? $booking->preferred_slot,
            ]);
        });

        $booking->refresh()->load(['provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders']);

        return response()->json(['booking' => $this->serialize($booking)]);
    }

    public function cancel(string $bookingCode): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ((int) $booking->user_id !== (int) Auth::id() && !$this->isAdmin(Auth::user())) {
            abort(403);
        }

        if (!in_array($booking->status, ['PENDING', 'OPEN', 'ACCEPTED'], true)) {
            throw ValidationException::withMessages([
                'status' => 'این رزرو قابل لغو نیست.',
            ]);
        }

        $booking->update(['status' => 'CANCELLED']);

        return response()->json(['booking' => $this->serialize($booking->fresh([
            'provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders',
        ]))]);
    }

    /**
     * Provider accept/reject for a direct PENDING booking.
     */
    public function respond(Request $request, string $bookingCode): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $data = $request->validate([
            'action' => ['required', Rule::in(['accept', 'reject'])],
        ]);

        $booking = Booking::query()
            ->with(['invitedProviders', 'provider', 'dog.breed', 'city', 'offers.provider'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        abort_unless($this->isAssignedProvider($booking, (int) Auth::id()), 403);

        if ($booking->fulfillment_mode !== 'direct' || strtoupper((string) $booking->status) !== 'PENDING') {
            throw ValidationException::withMessages([
                'booking' => 'فقط درخواست‌های مستقیم در انتظار قابل پاسخ هستند.',
            ]);
        }

        if ($data['action'] === 'accept') {
            $booking->update([
                'provider_id' => Auth::id(),
                'status' => 'ACCEPTED',
            ]);
        } else {
            $booking->update(['status' => 'CANCELLED']);
        }

        return response()->json([
            'booking' => $this->serialize($booking->fresh([
                'provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders',
            ])),
        ]);
    }

    /**
     * Customer rejects one open offer without closing the booking.
     */
    public function rejectOffer(string $bookingCode, int $offerId): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['offers'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if ((int) $booking->user_id !== (int) Auth::id() && ! $this->isAdmin(Auth::user())) {
            abort(403);
        }

        if (strtoupper((string) $booking->status) !== 'OPEN') {
            throw ValidationException::withMessages([
                'booking' => 'این درخواست دیگر باز نیست.',
            ]);
        }

        $offer = $booking->offers->firstWhere('id', $offerId);
        if (! $offer || $offer->status !== 'pending') {
            throw ValidationException::withMessages([
                'offer_id' => 'پیشنهاد معتبر نیست.',
            ]);
        }

        $offer->update(['status' => 'rejected']);

        return response()->json([
            'offer' => $this->serializeOffer($offer->fresh('provider')),
            'booking' => $this->serialize($booking->fresh([
                'provider', 'breed', 'city', 'dog.breed', 'offers.provider', 'invitedProviders',
            ])),
        ]);
    }

    public function messages(string $bookingCode): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['invitedProviders', 'offers'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        abort_unless($this->canChat($booking, (int) Auth::id()), 403);

        $messages = BookingMessage::query()
            ->with('user')
            ->where('booking_id', $booking->id)
            ->orderBy('id')
            ->get()
            ->map(fn (BookingMessage $m) => $this->serializeMessage($m));

        return response()->json(['results' => $messages]);
    }

    public function storeMessage(Request $request, string $bookingCode): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['invitedProviders', 'offers'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        abort_unless($this->canChat($booking, (int) Auth::id()), 403);

        if (in_array(strtoupper((string) $booking->status), ['CANCELLED', 'COMPLETED'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'گفتگو برای این رزرو بسته است.',
            ]);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'booking_offer_id' => ['nullable', 'integer', 'exists:booking_offers,id'],
        ]);

        if (! empty($data['booking_offer_id'])) {
            $ownsOffer = BookingOffer::query()
                ->where('id', $data['booking_offer_id'])
                ->where('booking_id', $booking->id)
                ->exists();
            if (! $ownsOffer) {
                throw ValidationException::withMessages([
                    'booking_offer_id' => 'پیشنهاد نامعتبر است.',
                ]);
            }
        }

        $message = BookingMessage::query()->create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'booking_offer_id' => $data['booking_offer_id'] ?? null,
            'body' => $data['body'],
        ])->load('user');

        return response()->json(['message' => $this->serializeMessage($message)], 201);
    }

    public function serialize(Booking $booking): array
    {
        $provider = $booking->provider;
        $display = $provider
            ? trim(($provider->name ?? '').' '.($provider->family ?? ''))
            : null;

        $preferred = $booking->preferred_date
            ? Carbon::parse($booking->preferred_date)
            : null;

        return [
            'id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'payment_method' => $booking->payment_method,
            'service_type' => $booking->service_type,
            'fulfillment_mode' => $booking->fulfillment_mode ?? 'direct',
            'training_type' => $booking->training_type,
            'dog' => $booking->dog ? [
                'id' => $booking->dog->id,
                'title' => $booking->dog->title,
                'breed_id' => $booking->dog->breed_id,
                'breed_name' => $booking->dog->breed?->name,
                'image' => $booking->dog->image,
            ] : null,
            'breed' => $booking->breed ? ['id' => $booking->breed->id, 'name' => $booking->breed->name] : null,
            'provider' => $provider ? [
                'id' => $provider->id,
                'display_name' => $display !== '' ? $display : $provider->name,
            ] : null,
            'invited_providers' => $booking->relationLoaded('invitedProviders')
                ? $booking->invitedProviders->map(function (User $user) {
                    $name = trim(($user->name ?? '').' '.($user->family ?? ''));

                    return [
                        'id' => $user->id,
                        'display_name' => $name !== '' ? $name : $user->name,
                    ];
                })->values()->all()
                : [],
            'appointment_date' => optional($booking->appointment_date)?->toIso8601String(),
            'appointment_jalali' => $booking->appointment_date
                ? Jalalian::fromCarbon($booking->appointment_date)->format('Y/m/d')
                : null,
            'slot' => $booking->slot ?: optional($booking->appointment_date)->format('H:i'),
            'preferred_date' => $preferred?->toDateString(),
            'preferred_jalali' => $preferred ? Jalalian::fromCarbon($preferred)->format('Y/m/d') : null,
            'preferred_slot' => $booking->preferred_slot,
            'price' => (float) $booking->price,
            'tax' => (float) $booking->tax,
            'discount' => (float) $booking->discount,
            'total' => (float) ($booking->total ?? $booking->price),
            'contact_name' => $booking->contact_name,
            'contact_phone' => $booking->contact_phone,
            'notes' => $booking->notes,
            'city' => $booking->city ? ['id' => $booking->city->id, 'name' => $booking->city->name] : null,
            'offers' => $booking->relationLoaded('offers')
                ? $booking->offers->map(fn (BookingOffer $o) => $this->serializeOffer($o))->values()->all()
                : [],
            'customer' => $booking->relationLoaded('user') && $booking->user ? [
                'id' => $booking->user->id,
                'display_name' => trim(($booking->user->name ?? '').' '.($booking->user->family ?? '')) ?: $booking->user->name,
            ] : null,
        ];
    }

    private function serializeDog(Dog $dog): array
    {
        return [
            'id' => $dog->id,
            'title' => $dog->title,
            'breed_id' => $dog->breed_id,
            'breed_name' => $dog->breed?->name,
            'image' => $dog->image,
            'age' => $dog->age,
            'color' => $dog->color,
            'status' => $dog->status,
        ];
    }

    private function serializeOffer(BookingOffer $offer): array
    {
        $provider = $offer->provider;
        $display = $provider
            ? trim(($provider->name ?? '').' '.($provider->family ?? ''))
            : null;

        return [
            'id' => $offer->id,
            'booking_id' => $offer->booking_id,
            'provider_id' => $offer->provider_id,
            'provider' => $provider ? [
                'id' => $provider->id,
                'display_name' => $display !== '' ? $display : $provider->name,
            ] : null,
            'price' => (float) $offer->price,
            'message' => $offer->message,
            'proposed_date' => optional($offer->proposed_date)?->toDateString(),
            'proposed_slot' => $offer->proposed_slot,
            'status' => $offer->status,
        ];
    }

    private function assertProviderRole(int $providerId, string $serviceType): void
    {
        $role = BookingCatalog::roleFor($serviceType);
        $ok = User::query()
            ->where('users.id', $providerId)
            ->whereHas('roles', fn ($q) => $q->where('name', $role))
            ->exists();

        if (!$ok) {
            throw ValidationException::withMessages([
                'provider_ids' => 'ارائه‌دهنده با نوع خدمت هم‌خوان نیست.',
            ]);
        }
    }

    private function slotTaken(int $providerId, Carbon $appointment, string $slot): bool
    {
        return Booking::query()
            ->where('provider_id', $providerId)
            ->where('status', '!=', 'CANCELLED')
            ->where(function ($q) use ($appointment, $slot) {
                $q->where(function ($inner) use ($appointment, $slot) {
                    $inner->whereDate('appointment_date', $appointment->toDateString())
                        ->where('slot', $slot);
                })->orWhere('appointment_date', $appointment);
            })
            ->exists();
    }

    private function canView(Booking $booking): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }
        if ($this->isAdmin($user)) {
            return true;
        }

        return $this->canChat($booking, (int) $user->id)
            || (
                strtoupper((string) $booking->status) === 'OPEN'
                && $booking->fulfillment_mode === 'open'
                && method_exists($user, 'hasRole')
                && $user->hasRole(BookingCatalog::roleFor((string) $booking->service_type))
            );
    }

    private function canChat(Booking $booking, int $userId): bool
    {
        if ((int) $booking->user_id === $userId) {
            return true;
        }
        if ((int) $booking->provider_id === $userId) {
            return true;
        }
        if ($this->isAssignedProvider($booking, $userId)) {
            return true;
        }

        $hasOffer = BookingOffer::query()
            ->where('booking_id', $booking->id)
            ->where('provider_id', $userId)
            ->exists();

        return $hasOffer;
    }

    private function isAssignedProvider(Booking $booking, int $userId): bool
    {
        if ((int) $booking->provider_id === $userId) {
            return true;
        }

        if ($booking->relationLoaded('invitedProviders')) {
            return $booking->invitedProviders->contains('id', $userId);
        }

        return $booking->invitedProviders()->where('provider_id', $userId)->exists();
    }

    private function serializeMessage(BookingMessage $message): array
    {
        $user = $message->user;
        $name = $user ? trim(($user->name ?? '').' '.($user->family ?? '')) : '';

        return [
            'id' => $message->id,
            'booking_id' => $message->booking_id,
            'booking_offer_id' => $message->booking_offer_id,
            'body' => $message->body,
            'created_at' => optional($message->created_at)?->toIso8601String(),
            'user' => $user ? [
                'id' => $user->id,
                'display_name' => $name !== '' ? $name : $user->name,
            ] : null,
            'mine' => Auth::check() && (int) $message->user_id === (int) Auth::id(),
        ];
    }

    private function isAdmin(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        if (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('is_admin'))) {
            return true;
        }

        return !empty($user->is_admin);
    }
}
