@php
    use App\Support\BookingCatalog;
    use Morilog\Jalali\Jalalian;

    $statusLabels = [
        'PENDING' => 'در انتظار',
        'OPEN' => 'باز',
        'QUOTED' => 'پیشنهاد شده',
        'ACCEPTED' => 'پذیرفته',
        'CONFIRMED' => 'تأیید شده',
        'COMPLETED' => 'انجام شده',
        'CANCELLED' => 'لغو شده',
    ];
    $statusKey = strtoupper((string) ($booking->status ?? ''));
    $serviceLabels = collect(BookingCatalog::serviceTypes())->pluck('label', 'key');
    $trainingLabels = collect(BookingCatalog::trainingTypes())->pluck('label', 'key');
    $modeLabels = collect(BookingCatalog::fulfillmentModes())->pluck('label', 'key');
    $serviceLabel = $serviceLabels[$booking->service_type] ?? strtoupper((string) $booking->service_type);
    $trainingLabel = $booking->training_type
        ? ($trainingLabels[$booking->training_type] ?? $booking->training_type)
        : null;
    $modeKey = $booking->fulfillment_mode ?? 'direct';
    $modeLabel = $modeLabels[$modeKey] ?? $modeKey;
    $cityName = is_object($booking->city ?? null) ? ($booking->city->name ?? null) : null;

    $preferredJalali = null;
    if (! empty($booking->preferred_date)) {
        try {
            $preferredJalali = Jalalian::fromDateTime($booking->preferred_date)->format('Y/m/d');
        } catch (\Throwable) {
            $preferredJalali = (string) $booking->preferred_date;
        }
    }
    $appointmentJalali = null;
    if (! empty($booking->appointment_date)) {
        try {
            $appointmentJalali = Jalalian::fromCarbon(\Carbon\Carbon::parse($booking->appointment_date))->format('Y/m/d H:i');
        } catch (\Throwable) {
            $appointmentJalali = (string) $booking->appointment_date;
        }
    }

    $customerName = null;
    if ($booking->user && empty($hideCustomer)) {
        $customerName = trim(($booking->user->name ?? '').' '.($booking->user->family ?? ''));
        $customerName = $customerName !== '' ? $customerName : ($booking->user->name ?? null);
    }
    $providerName = null;
    if ($booking->provider) {
        $providerName = trim(($booking->provider->name ?? '').' '.($booking->provider->family ?? ''));
        $providerName = $providerName !== '' ? $providerName : ($booking->provider->name ?? null);
    }

    $bookingRows = [
        ['کد رزرو', $booking->booking_code, true],
        ['وضعیت', $statusLabels[$statusKey] ?? $booking->status, false],
        ['نوع خدمت', $serviceLabel, false],
        ['نحوه انجام', $modeLabel, false],
        ['نوع آموزش', $trainingLabel, false],
        ['شهر', $cityName, false],
        ['تاریخ ترجیحی', $preferredJalali, true],
        ['ساعت ترجیحی', $booking->preferred_slot ?? null, true],
        ['زمان قرار', $appointmentJalali, true],
        ['بازه', $booking->slot ?? null, true],
        ['مبلغ', number_format((float) ($booking->total ?? $booking->price ?? 0)).' ت', true],
        ['مشتری', $customerName, false],
        ['ارائه‌دهنده', $providerName, false],
    ];

    $dog = $booking->dog;
    $dogBirthJalali = null;
    if ($dog && ! empty($dog->birth_date)) {
        try {
            $dogBirthJalali = Jalalian::fromDateTime($dog->birth_date)->format('Y/m/d');
        } catch (\Throwable) {
            $dogBirthJalali = (string) $dog->birth_date;
        }
    }
    $dogRows = [];
    if ($dog) {
        $dogRows = [
            ['نام', $dog->title, false],
            ['نژاد', $dog->breedName(), false],
            ['سن (ماه)', $dog->age !== null ? (string) $dog->age : null, true],
            ['تاریخ تولد', $dogBirthJalali, true],
            ['رنگ', $dog->color, false],
            ['رنگ چشم', $dog->eye_color, false],
            ['نوع پوشش', $dog->cover_type, false],
            ['وضعیت', $dog->status, false],
        ];
    }

    $dogImages = [];
    if ($dog) {
        if ($dog->relationLoaded('media') && $dog->media->isNotEmpty()) {
            foreach ($dog->media as $media) {
                $dogImages[] = method_exists($media, 'publicUrl') ? $media->publicUrl() : ($media->path ?? null);
            }
        }
        if ($dogImages === [] && ! empty($dog->image)) {
            $dogImages[] = $dog->image;
        }
    }
@endphp

<section class="sp-dash__panel sp-booking-details">
    <header class="sp-dash__panel-head">
        <h2>جزئیات رزرو</h2>
        <span class="sp-dash__chip">{{ $statusLabels[$statusKey] ?? $statusKey }}</span>
    </header>
    <div class="sp-booking-details__grid">
        @foreach($bookingRows as [$label, $value, $isNum])
            @continue($value === null || $value === '')
            <div class="sp-booking-details__item">
                <span class="sp-booking-details__label">{{ $label }}</span>
                <strong class="{{ $isNum ? 'num' : '' }}">{{ $value }}</strong>
            </div>
        @endforeach
    </div>
</section>

@if($dog)
    <section class="sp-dash__panel sp-booking-details">
        <header class="sp-dash__panel-head">
            <h2>جزئیات سگ</h2>
        </header>
        <div class="sp-booking-details__dog">
            @if($dogImages !== [])
                <div class="sp-booking-details__gallery">
                    @foreach($dogImages as $img)
                        @continue(! $img)
                        <img src="{{ str_starts_with($img, 'http') || str_starts_with($img, '/') ? $img : asset($img) }}" alt="{{ $dog->title }}">
                    @endforeach
                </div>
            @endif
            <div class="sp-booking-details__grid">
                @foreach($dogRows as [$label, $value, $isNum])
                    @continue($value === null || $value === '')
                    <div class="sp-booking-details__item">
                        <span class="sp-booking-details__label">{{ $label }}</span>
                        <strong class="{{ $isNum ? 'num' : '' }}">{{ $value }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif