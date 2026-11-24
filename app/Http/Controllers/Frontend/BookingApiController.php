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
        // Prefer the lowest id per city name so duplicate seed rows don't confuse the wizard.
        $rows = City::query()
            ->when($term !== '', fn ($q) => $q->where('name', 'like', '%'.$term.'%'))
            ->orderBy('id')
            ->get(['id', 'name'])
            ->unique(fn (City $c) => mb_strtolower(trim((string) $c->name)))
            ->sortBy(fn (City $c) => $c->name, SORT_NATURAL | SORT_FLAG_CASE)
            ->take(40)
            ->values()
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
        // Treat 0 / "" as "no city filter" so the wizard can boot before city is chosen.
        if ($request->exists('city_id') && (int) $request->input('city_id') < 1) {
            $request->merge(['city_id' => null]);
        }

        $validated = $request->validate([
            'service_type' => ['required', Rule::in(BookingCatalog::keys())],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'breed_id' => ['nullable', 'integer', 'exists:breeds,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $role = BookingCatalog::roleFor($validated['service_type']);
        $term = trim((string) ($validated['q'] ?? ''));
        $cityIds = $this->cityIdsMatchingSelection(
            isset($validated['city_id']) ? (int) $validated['city_id'] : null
        );

        $query = User::query()
            ->select('users.*')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', $role)
            ->with('city:id,name')
            ->orderBy('users.name');

        // Direct booking: only providers in the selected city (including duplicate city rows with the same name).
        if ($cityIds !== null) {
            if ($cityIds === []) {
                return response()->json([
                    'results' => [],
                    'city_id' => $validated['city_id'] ?? null,
                    'city_name' => null,
                    'empty' => true,
                ]);
            }
            $query->whereIn('users.city_id', $cityIds);
        }

        if ($term !== '') {
            $like = '%'.$term.'%';
            $query->where(function ($builder) use ($like) {
                $builder->where('users.name', 'like', $like)
                    ->orWhere('users.family', 'like', $like);
            });
        }

        $serviceType = $validated['service_type'];
        $cityName = null;
        if (! empty($validated['city_id'])) {
            $cityName = optional(City::query()->find($validated['city_id']))->name;
        }

        $results = $query->limit(40)->get()->map(function (User $user) use ($role, $serviceType) {
            $display = trim(($user->name ?? '').' '.($user->family ?? ''));
            $priceRow = BookingCatalog::providerPriceRow((int) $user->id, $serviceType);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'family' => $user->family,
                'display_name' => $display !== '' ? $display : ($user->name ?? 'بدون نام'),
                'city' => $user->city ? ['id' => $user->city->id, 'name' => $user->city->name] : null,
                'photo' => $user->profile_picture ?? null,
                'role' => $role,
                'price' => BookingCatalog::priceForProvider((int) $user->id, $serviceType),
                'price_source' => ($priceRow && (int) $priceRow->base_price > 0) ? 'provider' : 'catalog',
            ];
        });

        return response()->json([
            'results' => $results,
            'city_id' => $validated['city_id'] ?? null,
            'city_name' => $cityName,
            'empty' => $results->isEmpty(),
        ]);
    }

    /**
     * Resolve all city ids that should match a wizard selection.
     * Duplicate city names in the DB (e.g. two «تهران» rows) must still return providers.
     *
     * @return list<int>|null null = no city filter
     */
    private function cityIdsMatchingSelection(?int $cityId): ?array
    {
        if ($cityId === null || $cityId < 1) {
            return null;
        }

        $city = City::query()->find($cityId);
        if (! $city) {
            return [];
        }

        $name = trim((string) $city->name);
        if ($name === '') {
            return [(int) $city->id];
        }

        return City::query()
            ->where('name', $name)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    public function slots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'service_type' => ['nullable', Rule::in(BookingCatalog::keys())],
            'provider_service_id' => ['nullable', 'integer', 'exists:provider_services,id'],
        ]);

        $tz = config('app.timezone', 'Asia/Tehran');
        $date = Carbon::createFromFormat('Y-m-d', $validated['date'], $tz)->startOfDay();
        $service = null;
        if (! empty($validated['provider_service_id'])) {
            $service = \App\Models\ProviderService::query()
                ->whereKey($validated['provider_service_id'])
                ->where('user_id', $validated['provider_id'])
                ->first();
        }

        $slots = app(\App\Support\BookingAvailabilityService::class)
            ->slotsFor((int) $validated['provider_id'], $date, $service);

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
            'provider_id' => ['nullable', 'integer', 'exists:users,id'],
            'provider_service_id' => ['nullable', 'integer', 'exists:provider_services,id'],
        ]);

        if ($validated['service_type'] === 'coach' && empty($validated['training_type'])) {
            throw ValidationException::withMessages([
                'training_type' => 'برای رزرو مربی، نوع آموزش الزامی است.',
            ]);
        }

        $providerId = isset($validated['provider_id']) ? (int) $validated['provider_id'] : null;
        $mode = $validated['fulfillment_mode'];
        $svc = $this->resolveProviderServiceForBooking(
            $validated['provider_service_id'] ?? null,
            $providerId,
            $validated['service_type'],
            $mode === 'direct' && $providerId > 0
        );

        if ($svc) {
            return response()->json(
                BookingCatalog::quoteForService(
                    $svc,
                    $mode,
                    $validated['training_type'] ?? null,
                    (int) ($validated['discount'] ?? 0),
                )
            );
        }

        return response()->json(
            BookingCatalog::quote(
                $validated['service_type'],
                $mode,
                $validated['training_type'] ?? null,
                (int) ($validated['discount'] ?? 0),
                $providerId,
            )
        );
    }

    public function providerServices(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'service_type' => ['nullable', Rule::in(BookingCatalog::keys())],
        ]);

        $q = \App\Models\ProviderService::query()
            ->with('specialty:id,name')
            ->where('user_id', $validated['provider_id'])
            ->where('is_active', true)
            ->orderBy('name');

        if (! empty($validated['service_type'])) {
            $q->where('service_type', $validated['service_type']);
        }

        return response()->json([
            'results' => $q->get()->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'description' => $s->description,
                'service_type' => $s->service_type,
                'base_price' => (int) $s->base_price,
                'duration_minutes' => (int) $s->duration_minutes,
                'specialty' => $s->specialty?->name,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $today = Carbon::now(config('app.timezone', 'Asia/Tehran'))->toDateString();

        // Normalize blank / Persian-digit dates before validation.
        foreach (['preferred_date', 'date'] as $dateKey) {
            $raw = $request->input($dateKey);
            if ($raw === null || $raw === '') {
                $request->merge([$dateKey => null]);
                continue;
            }
            if (is_string($raw)) {
                $normalized = $this->normalizeDateInput($raw);
                $request->merge([$dateKey => $normalized]);
            }
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
            'provider_service_id' => ['nullable', 'integer', 'exists:provider_services,id'],
            'preferred_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$today],
            'preferred_slot' => ['nullable', 'string', 'max:8'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.$today],
            'slot' => ['nullable', 'string', 'max:8'],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', Rule::in(['wallet', 'gateway', 'offline', 'online'])],
        ], [
            'preferred_date.after_or_equal' => 'تاریخ ترجیحی باید امروز یا روزهای بعد باشد.',
            'preferred_date.date_format' => 'قالب تاریخ ترجیحی معتبر نیست.',
            'date.after_or_equal' => 'تاریخ باید امروز یا روزهای بعد باشد.',
            'date.date_format' => 'قالب تاریخ معتبر نیست.',
            'city_id.required' => 'شهر را انتخاب کنید.',
            'dog_id.required' => 'سگ را انتخاب کنید.',
            'contact_name.required' => 'نام تماس الزامی است.',
            'contact_phone.required' => 'شماره تماس الزامی است.',
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

        $primaryProviderId = $mode === 'direct' ? ($providerIds[0] ?? null) : null;
        $providerService = $this->resolveProviderServiceForBooking(
            $validated['provider_service_id'] ?? null,
            $primaryProviderId,
            $validated['service_type'],
            $mode === 'direct'
        );

        if ($mode === 'direct' && $primaryProviderId && $preferredDate && $preferredSlot) {
            $svcForSlot = $providerService;
            $ok = app(\App\Support\BookingAvailabilityService::class)->assertSlotAvailable(
                (int) $primaryProviderId,
                Carbon::createFromFormat('Y-m-d', $preferredDate, config('app.timezone', 'Asia/Tehran')),
                substr((string) $preferredSlot, 0, 5),
                $svcForSlot
            );
            if (! $ok) {
                throw ValidationException::withMessages([
                    'preferred_slot' => 'این بازه زمانی در دسترس نیست.',
                ]);
            }
        }

        $quote = $providerService
            ? BookingCatalog::quoteForService($providerService, $mode, $validated['training_type'] ?? null)
            : BookingCatalog::quote(
                $validated['service_type'],
                $mode,
                $validated['training_type'] ?? null,
                0,
                $primaryProviderId,
            );

        $requestedPay = $validated['payment_method'] ?? 'gateway';
        if ($requestedPay === 'online') {
            $requestedPay = 'gateway';
        }
        // DB enum is online|offline; wallet/gateway preference stored in gateway_driver.
        $paymentMethod = $requestedPay === 'offline' ? 'offline' : 'online';
        $gatewayDriver = match ($requestedPay) {
            'wallet' => 'wallet',
            'gateway' => (string) config('payment.default', 'local'),
            default => null,
        };

        $booking = DB::transaction(function () use ($validated, $dog, $mode, $providerIds, $preferredDate, $preferredSlot, $appointment, $slot, $quote, $primaryProviderId, $paymentMethod, $gatewayDriver, $providerService) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'dog_id' => $dog->id,
                'service_type' => $validated['service_type'],
                'fulfillment_mode' => $mode,
                'provider_id' => $primaryProviderId,
                'provider_service_id' => $providerService?->id,
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
                'payment_method' => $paymentMethod,
                'gateway_driver' => $gatewayDriver,
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

        $pay = app(\App\Support\BookingPaymentService::class);
        $successUrl = $pay->needsOnlinePayment($booking)
            ? route('frontend.booking.pay', $booking->booking_code, false)
            : route('frontend.booking.success', $booking->booking_code, false);

        return response()->json([
            'booking' => $this->serialize($booking),
            'success_url' => $successUrl,
            'payment_required' => $pay->needsOnlinePayment($booking),
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
            'proposed_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:'.Carbon::now(config('app.timezone', 'Asia/Tehran'))->toDateString()],
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

        $booking = app(\App\Support\BookingLifecycleService::class)
            ->cancel($booking, 'cancel');

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

        $userId = (int) Auth::id();
        $canRespond = $this->isAssignedProvider($booking, $userId)
            || (
                ($booking->fulfillment_mode ?? 'direct') === 'open'
                && method_exists(Auth::user(), 'hasRole')
                && Auth::user()->hasRole(BookingCatalog::roleFor((string) $booking->service_type))
            );

        abort_unless($canRespond, 403);

        $status = strtoupper((string) $booking->status);
        if (in_array($status, ['ACCEPTED', 'CONFIRMED', 'COMPLETED', 'CANCELLED', 'REJECTED'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'این درخواست قبلاً تصمیم‌گیری شده است.',
            ]);
        }

        // Open marketplace: only the customer accepts an offer; providers must not cancel the listing.
        if (($booking->fulfillment_mode ?? 'direct') === 'open') {
            throw ValidationException::withMessages([
                'booking' => 'برای درخواست باز، پیشنهاد بفرستید؛ پذیرش نهایی با صاحب درخواست است.',
            ]);
        }

        $pay = app(\App\Support\BookingPaymentService::class);
        $life = app(\App\Support\BookingLifecycleService::class);

        if ($data['action'] === 'accept') {
            $booking->update([
                'provider_id' => $userId,
                'status' => 'ACCEPTED',
            ]);
            $booking = $booking->fresh();
            if ($booking->isHeld()) {
                $booking = $pay->settleHeld($booking);
            }
        } else {
            $rejectionReason = $request->input('reason');
            $booking = $life->reject(
                $booking,
                'reject',
                is_string($rejectionReason) ? $rejectionReason : null
            );
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

    /**
     * List 1:1 chat threads for a booking (keyed by booking + provider).
     */
    public function threads(string $bookingCode): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['invitedProviders', 'offers.provider', 'provider'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        abort_unless($this->canChat($booking, (int) Auth::id()), 403);

        $userId = (int) Auth::id();
        $isOwner = (int) $booking->user_id === $userId;

        $providerIds = collect();
        if ($isOwner || $this->isAdmin(Auth::user())) {
            $providerIds = $providerIds
                ->merge($booking->offers->pluck('provider_id'))
                ->merge(
                    BookingMessage::query()
                        ->where('booking_id', $booking->id)
                        ->whereNotNull('provider_id')
                        ->pluck('provider_id')
                );
            if ($booking->provider_id) {
                $providerIds->push($booking->provider_id);
            }
            $providerIds = $providerIds->merge(
                $booking->invitedProviders->pluck('id')
            )->unique()->filter()->values();
        } else {
            $providerIds = collect([$userId]);
        }

        $users = User::query()->whereIn('id', $providerIds->all())->get()->keyBy('id');
        $counts = BookingMessage::query()
            ->selectRaw('provider_id, COUNT(*) as c')
            ->where('booking_id', $booking->id)
            ->whereIn('provider_id', $providerIds->all() ?: [0])
            ->groupBy('provider_id')
            ->pluck('c', 'provider_id');

        $results = $providerIds->map(function ($pid) use ($users, $counts, $booking) {
            $user = $users->get($pid);
            if (! $user) {
                return null;
            }
            $name = trim(($user->name ?? '').' '.($user->family ?? ''));

            return [
                'provider_id' => (int) $pid,
                'display_name' => $name !== '' ? $name : $user->name,
                'message_count' => (int) ($counts[$pid] ?? 0),
                'has_offer' => $booking->offers->contains('provider_id', (int) $pid),
            ];
        })->filter()->values();

        return response()->json(['results' => $results]);
    }

    public function messages(Request $request, string $bookingCode): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $booking = Booking::query()
            ->with(['invitedProviders', 'offers'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        abort_unless($this->canChat($booking, (int) Auth::id()), 403);

        $providerId = $this->resolveThreadProviderId($request, $booking);

        $messages = BookingMessage::query()
            ->with('user')
            ->where('booking_id', $booking->id)
            ->where('provider_id', $providerId)
            ->orderBy('id')
            ->get()
            ->map(fn (BookingMessage $m) => $this->serializeMessage($m));

        $isOwner = (int) $booking->user_id === (int) Auth::id();
        $canReply = $isOwner
            || $this->isAdmin(Auth::user())
            || $this->ownerHasMessagedFirst($booking, $providerId);

        return response()->json([
            'provider_id' => $providerId,
            'owner_started' => $this->ownerHasMessagedFirst($booking, $providerId),
            'can_reply' => $canReply,
            'results' => $messages,
        ]);
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

        if (in_array(strtoupper((string) $booking->status), ['CANCELLED', 'REJECTED', 'COMPLETED'], true)) {
            throw ValidationException::withMessages([
                'booking' => 'گفتگو برای این رزرو بسته است.',
            ]);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'provider_id' => ['nullable', 'integer', 'exists:users,id'],
            'booking_offer_id' => ['nullable', 'integer', 'exists:booking_offers,id'],
        ]);

        $providerId = $this->resolveThreadProviderId($request, $booking);
        $senderId = (int) Auth::id();
        $isOwner = (int) $booking->user_id === $senderId;

        // Non-owners (providers) may only reply after: (1) they sent an offer / were requested,
        // and (2) the booking reserver messaged first in this booking+provider thread.
        if (! $isOwner && ! $this->isAdmin(Auth::user())) {
            $hasOfferOrAssignment = $this->isAssignedProvider($booking, $senderId)
                || BookingOffer::query()
                    ->where('booking_id', $booking->id)
                    ->where('provider_id', $senderId)
                    ->exists();

            if (! $hasOfferOrAssignment) {
                throw ValidationException::withMessages([
                    'booking' => 'برای گفتگو ابتدا باید پیشنهاد/درخواست ارسال کنید.',
                ]);
            }

            if (! $this->ownerHasMessagedFirst($booking, $providerId)) {
                throw ValidationException::withMessages([
                    'booking' => 'گفتگو وقتی شروع می‌شود که صاحب رزرو اول پیام بدهد.',
                ]);
            }
        }

        if (! empty($data['booking_offer_id'])) {
            $offer = BookingOffer::query()
                ->where('id', $data['booking_offer_id'])
                ->where('booking_id', $booking->id)
                ->first();
            if (! $offer || (int) $offer->provider_id !== $providerId) {
                throw ValidationException::withMessages([
                    'booking_offer_id' => 'پیشنهاد نامعتبر است.',
                ]);
            }
        }

        $message = BookingMessage::query()->create([
            'booking_id' => $booking->id,
            'user_id' => $senderId,
            'provider_id' => $providerId,
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
            'settlement_status' => $booking->settlement_status ?? 'none',
            'held_amount' => (int) ($booking->held_amount ?? 0),
            'provider_service_id' => $booking->provider_service_id ? (int) $booking->provider_service_id : null,
            'service_type' => $booking->service_type,
            'fulfillment_mode' => $booking->fulfillment_mode ?? 'direct',
            'training_type' => $booking->training_type,
            'dog' => $booking->dog ? [
                'id' => $booking->dog->id,
                'title' => $booking->dog->title,
                'breed_id' => $booking->dog->breed_id,
                'breed_name' => $booking->dog->breedName(),
                'image' => $booking->dog->image,
            ] : null,
            'breed' => $this->serializeBreedRelation($booking),
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
            'city' => $this->serializeCityRelation($booking),
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
            'breed_name' => $dog->breedName(),
            'image' => $dog->image,
            'age' => $dog->age,
            'color' => $dog->color,
            'status' => $dog->status,
        ];
    }

    private function serializeBreedRelation(Booking $booking): ?array
    {
        $breed = $booking->relationLoaded('breed')
            ? $booking->getRelation('breed')
            : (is_object($booking->breed ?? null) ? $booking->breed : $booking->breed()->first());

        if (! is_object($breed)) {
            return null;
        }

        return ['id' => $breed->id, 'name' => $breed->name];
    }

    private function serializeCityRelation(Booking $booking): ?array
    {
        $city = $booking->relationLoaded('city')
            ? $booking->getRelation('city')
            : (is_object($booking->city ?? null) ? $booking->city : $booking->city()->first());

        if (! is_object($city)) {
            return null;
        }

        return ['id' => $city->id, 'name' => $city->name];
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
            ->whereNotIn('status', BookingCatalog::closedStatuses())
            ->where(function ($q) use ($appointment, $slot) {
                $q->where(function ($inner) use ($appointment, $slot) {
                    $inner->whereDate('appointment_date', $appointment->toDateString())
                        ->where('slot', $slot);
                })->orWhere('appointment_date', $appointment);
            })
            ->exists();
    }

    /**
     * Resolve and validate a provider service for quote/store.
     * Direct bookings require an active matching service when the provider has any.
     */
    private function resolveProviderServiceForBooking(
        mixed $serviceId,
        ?int $primaryProviderId,
        string $serviceType,
        bool $requireWhenProviderHasServices
    ): ?\App\Models\ProviderService {
        $hasActive = $primaryProviderId
            ? \App\Models\ProviderService::query()
                ->where('user_id', $primaryProviderId)
                ->where('service_type', $serviceType)
                ->active()
                ->exists()
            : false;

        if ($requireWhenProviderHasServices && $primaryProviderId && $hasActive && empty($serviceId)) {
            throw ValidationException::withMessages([
                'provider_service_id' => 'انتخاب خدمت ارائه‌دهنده الزامی است.',
            ]);
        }

        if (empty($serviceId)) {
            return null;
        }

        $providerService = \App\Models\ProviderService::query()->findOrFail((int) $serviceId);

        if ($primaryProviderId && (int) $providerService->user_id !== (int) $primaryProviderId) {
            throw ValidationException::withMessages([
                'provider_service_id' => 'خدمت متعلق به ارائه‌دهنده انتخاب‌شده نیست.',
            ]);
        }

        if (! $providerService->is_active) {
            throw ValidationException::withMessages([
                'provider_service_id' => 'این خدمت فعال نیست.',
            ]);
        }

        if ((string) $providerService->service_type !== $serviceType) {
            throw ValidationException::withMessages([
                'provider_service_id' => 'نوع خدمت با نقش/خدمت رزرو هم‌خوان نیست.',
            ]);
        }

        return $providerService;
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
        // Booking owner (reserver) can always open chat threads.
        if ((int) $booking->user_id === $userId) {
            return true;
        }

        // Direct: invited / assigned provider may chat after the customer requested them.
        if ($this->isAssignedProvider($booking, $userId)) {
            return true;
        }

        // Open: provider may chat only after they submitted an offer/request.
        return BookingOffer::query()
            ->where('booking_id', $booking->id)
            ->where('provider_id', $userId)
            ->exists();
    }

    /**
     * Providers may reply only after the booking reserver has messaged first in this thread.
     */
    private function ownerHasMessagedFirst(Booking $booking, int $providerId): bool
    {
        return BookingMessage::query()
            ->where('booking_id', $booking->id)
            ->where('provider_id', $providerId)
            ->where('user_id', $booking->user_id)
            ->exists();
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

    /**
     * Resolve the provider side of a private thread (booking_id + provider_id).
     */
    private function resolveThreadProviderId(Request $request, Booking $booking): int
    {
        $userId = (int) Auth::id();
        $isOwner = (int) $booking->user_id === $userId;
        $requested = (int) ($request->input('provider_id') ?: $request->query('provider_id') ?: 0);

        // Provider always chats in their own thread.
        if (! $isOwner && ! $this->isAdmin(Auth::user())) {
            if (! $this->canChat($booking, $userId)) {
                abort(403);
            }

            return $userId;
        }

        // Customer / admin must pick a provider thread.
        if ($requested < 1) {
            if ($booking->provider_id) {
                $requested = (int) $booking->provider_id;
            } elseif ($booking->relationLoaded('offers') && $booking->offers->isNotEmpty()) {
                $requested = (int) $booking->offers->first()->provider_id;
            }
        }

        if ($requested < 1) {
            throw ValidationException::withMessages([
                'provider_id' => 'برای گفتگو باید طرف مقابل (ارائه‌دهنده) مشخص باشد.',
            ]);
        }

        // Thread only with invited/assigned providers or those who already sent an offer.
        $allowed = (int) $booking->provider_id === $requested
            || $booking->invitedProviders()->where('provider_id', $requested)->exists()
            || BookingOffer::query()->where('booking_id', $booking->id)->where('provider_id', $requested)->exists();

        if (! $allowed) {
            throw ValidationException::withMessages([
                'provider_id' => 'گفتگو فقط پس از ارسال پیشنهاد/درخواست توسط ارائه‌دهنده ممکن است.',
            ]);
        }

        return $requested;
    }

    private function serializeMessage(BookingMessage $message): array
    {
        $user = $message->user;
        $name = $user ? trim(($user->name ?? '').' '.($user->family ?? '')) : '';

        return [
            'id' => $message->id,
            'booking_id' => $message->booking_id,
            'provider_id' => $message->provider_id,
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

    private function normalizeDateInput(string $raw): ?string
    {
        $value = trim($raw);
        if ($value === '') {
            return null;
        }

        $western = strtr($value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $western)) {
            return $western;
        }

        return $western !== '' ? $western : null;
    }
}
