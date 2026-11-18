<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Breed;
use App\Models\User;
use App\Support\BookingCatalog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Morilog\Jalali\Jalalian;

class BookingApiController extends Controller
{
    public function meta(): JsonResponse
    {
        return response()->json([
            'service_types' => BookingCatalog::serviceTypes(),
            'training_types' => BookingCatalog::trainingTypes(),
            'slot_template' => BookingCatalog::slotTemplate(),
            'currency' => 'IRT',
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

    public function store(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validated = $request->validate([
            'service_type' => ['required', Rule::in(BookingCatalog::keys())],
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'breed_id' => ['nullable', 'integer', 'exists:breeds,id'],
            'training_type' => ['nullable', Rule::in(BookingCatalog::trainingKeys())],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'slot' => ['required', Rule::in(BookingCatalog::slotTemplate())],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', Rule::in(['online', 'offline'])],
        ]);

        if ($validated['service_type'] === 'coach') {
            if (empty($validated['breed_id']) || empty($validated['training_type'])) {
                throw ValidationException::withMessages([
                    'breed_id' => 'برای رزرو مربی، نژاد و نوع آموزش الزامی است.',
                    'training_type' => 'برای رزرو مربی، نژاد و نوع آموزش الزامی است.',
                ]);
            }
        }

        $this->assertProviderRole((int) $validated['provider_id'], $validated['service_type']);

        $appointment = Carbon::createFromFormat('Y-m-d H:i', $validated['date'].' '.$validated['slot']);
        if ($this->slotTaken((int) $validated['provider_id'], $appointment, $validated['slot'])) {
            throw ValidationException::withMessages([
                'slot' => 'این بازه زمانی دیگر در دسترس نیست.',
            ]);
        }

        $price = BookingCatalog::priceFor($validated['service_type']);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'provider_id' => $validated['provider_id'],
            'city_id' => $validated['city_id'] ?? null,
            'breed_id' => $validated['breed_id'] ?? null,
            'training_type' => $validated['training_type'] ?? null,
            'appointment_date' => $appointment,
            'slot' => $validated['slot'],
            'booking_date' => now()->toDateString(),
            'price' => $price,
            'tax' => 0,
            'discount' => 0,
            'total' => $price,
            'payment_method' => $validated['payment_method'] ?? 'online',
            'payment_status' => 'pending',
            'status' => 'PENDING',
            'notes' => $validated['notes'] ?? null,
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
        ]);

        $booking->load(['provider', 'breed', 'city']);

        return response()->json([
            'booking' => $this->serialize($booking),
            'success_url' => route('frontend.booking.success', $booking->booking_code, false),
        ], 201);
    }

    public function show(string $bookingCode): JsonResponse
    {
        $booking = Booking::query()
            ->with(['provider', 'breed', 'city', 'user'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        if (!$this->canView($booking)) {
            abort(403);
        }

        return response()->json(['booking' => $this->serialize($booking)]);
    }

    public function serialize(Booking $booking): array
    {
        $provider = $booking->provider;
        $display = $provider
            ? trim(($provider->name ?? '').' '.($provider->family ?? ''))
            : null;

        return [
            'id' => $booking->id,
            'booking_code' => $booking->booking_code,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'payment_method' => $booking->payment_method,
            'service_type' => $booking->service_type,
            'training_type' => $booking->training_type,
            'breed' => $booking->breed ? ['id' => $booking->breed->id, 'name' => $booking->breed->name] : null,
            'provider' => $provider ? [
                'id' => $provider->id,
                'display_name' => $display !== '' ? $display : $provider->name,
            ] : null,
            'appointment_date' => optional($booking->appointment_date)?->toIso8601String(),
            'appointment_jalali' => $booking->appointment_date
                ? Jalalian::fromCarbon($booking->appointment_date)->format('Y/m/d')
                : null,
            'slot' => $booking->slot ?: optional($booking->appointment_date)->format('H:i'),
            'price' => (float) $booking->price,
            'tax' => (float) $booking->tax,
            'discount' => (float) $booking->discount,
            'total' => (float) ($booking->total ?? $booking->price),
            'contact_name' => $booking->contact_name,
            'contact_phone' => $booking->contact_phone,
            'notes' => $booking->notes,
            'city' => $booking->city ? ['id' => $booking->city->id, 'name' => $booking->city->name] : null,
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
                'provider_id' => 'ارائه‌دهنده با نوع خدمت هم‌خوان نیست.',
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
        if (!$user) {
            return false;
        }
        if ((int) $booking->user_id === (int) $user->id) {
            return true;
        }
        if (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('is_admin'))) {
            return true;
        }
        if (!empty($user->is_admin)) {
            return true;
        }

        return false;
    }
}