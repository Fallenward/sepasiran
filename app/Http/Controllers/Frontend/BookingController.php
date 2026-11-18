<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\City;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private array $serviceLabels = [
        'coach' => 'مربی',
        'clinic' => 'کلینیک',
        'breeder' => 'پرورش‌دهنده',
        'insurance' => 'بیمه',
    ];

    private array $defaultPrices = [
        'coach' => 1200000,
        'clinic' => 850000,
        'breeder' => 500000,
        'insurance' => 1500000,
    ];

    private array $slots = ['09:00', '11:00', '14:30', '16:00', '18:00', '20:00'];

    public function index(Request $request)
    {
        $serviceType = $request->get('type', 'coach');
        if (!array_key_exists($serviceType, $this->serviceLabels)) {
            $serviceType = 'coach';
        }

        $cities = City::query()->orderBy('name')->get(['id', 'name']);
        $providers = $this->providersFor($serviceType);
        $selectedProvider = (int) $request->get('provider', 0);

        return view('frontend.pages.booking.index', [
            'serviceLabels' => $this->serviceLabels,
            'defaultPrices' => $this->defaultPrices,
            'slots' => $this->slots,
            'cities' => $cities,
            'providers' => $providers,
            'serviceType' => $serviceType,
            'selectedProvider' => $selectedProvider,
            'prefillDate' => $request->get('date', now()->addDay()->format('Y-m-d')),
            'prefillSlot' => $request->get('slot', '11:00'),
        ]);
    }

    public function providers(Request $request)
    {
        $type = $request->get('type', 'coach');
        if (!array_key_exists($type, $this->serviceLabels)) {
            return response()->json(['results' => []]);
        }

        $term = trim((string) $request->get('q', ''));
        $rows = $this->providersFor($type, $term)->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $user->name,
            'city' => $user->city->name ?? null,
        ]);

        return response()->json(['results' => $rows]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()
                ->guest(route('login'))
                ->with('error', 'برای ثبت رزرو ابتدا وارد شوید.');
        }

        $validated = $request->validate([
            'service_type' => ['required', Rule::in(array_keys($this->serviceLabels))],
            'provider_id' => ['required', 'integer', 'exists:users,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'slot' => ['required', Rule::in($this->slots)],
            'contact_name' => ['required', 'string', 'max:120'],
            'contact_phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['nullable', Rule::in(['online', 'offline'])],
        ]);

        $price = $this->defaultPrices[$validated['service_type']] ?? 0;
        $appointment = Carbon::parse($validated['appointment_date'] . ' ' . $validated['slot'] . ':00');

        $notes = trim(($validated['notes'] ?? ''));
        $contactLine = 'تماس: ' . $validated['contact_name'] . ' | ' . $validated['contact_phone'];
        $notes = $notes === '' ? $contactLine : ($contactLine . "\n" . $notes);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'provider_id' => $validated['provider_id'],
            'city_id' => $validated['city_id'] ?: null,
            'appointment_date' => $appointment,
            'booking_date' => now()->toDateString(),
            'price' => $price,
            'tax' => 0,
            'discount' => 0,
            'total' => $price,
            'payment_method' => $validated['payment_method'] ?? 'online',
            'payment_status' => 'pending',
            'status' => 'PENDING',
            'notes' => $notes,
        ]);

        return redirect()
            ->route('frontend.booking.success', $booking->id)
            ->with('success', 'رزرو با موفقیت ثبت شد.');
    }

    public function success($id)
    {
        $booking = Booking::with(['provider', 'city', 'user'])->findOrFail($id);

        if (Auth::check() && (int) $booking->user_id !== (int) Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('frontend.pages.booking.success', [
            'booking' => $booking,
            'serviceLabels' => $this->serviceLabels,
        ]);
    }

    private function providersFor(string $type, string $term = '')
    {
        $role = match ($type) {
            'coach' => 'coach',
            'clinic' => 'clinic',
            'breeder' => 'breeder',
            default => 'coach',
        };

        // insurance: show clinics as providers for consultation booking
        if ($type === 'insurance') {
            $role = 'clinic';
        }

        $query = User::query()
            ->select('users.*')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', $role)
            ->with('city:id,name')
            ->orderBy('users.name');

        if ($term !== '') {
            $like = '%' . $term . '%';
            $query->where(function ($builder) use ($like) {
                $builder->where('users.name', 'like', $like)
                    ->orWhere('users.family', 'like', $like);
            });
        }

        return $query->limit(40)->get();
    }
}