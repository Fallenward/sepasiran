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
    $breedName = $booking->dog?->breedName()
        ?? (is_object($booking->breed ?? null) ? ($booking->breed->name ?? null) : null);
    $preferredJalali = null;
    if (!empty($booking->preferred_date)) {
        try {
            $preferredJalali = Jalalian::fromDateTime($booking->preferred_date)->format('Y/m/d');
        } catch (\Throwable) {
            $preferredJalali = (string) $booking->preferred_date;
        }
    }
    $appointmentJalali = null;
    if (!empty($booking->appointment_date)) {
        try {
            $appointmentJalali = Jalalian::fromCarbon(\Carbon\Carbon::parse($booking->appointment_date))->format('Y/m/d H:i');
        } catch (\Throwable) {
            $appointmentJalali = (string) $booking->appointment_date;
        }
    }
    $showContact = $showContact ?? (auth()->check() && (
        (int) ($booking->user_id ?? 0) === (int) auth()->id()
        || (int) ($booking->provider_id ?? 0) === (int) auth()->id()
        || (auth()->user() && method_exists(auth()->user(), 'hasRole') && (
            auth()->user()->hasRole('admin')
            || auth()->user()->hasRole(BookingCatalog::roleFor((string) $booking->service_type))
        ))
    ));
    $rows = [
        ['کد رزرو', $booking->booking_code, true],
        ['وضعیت', $statusLabels[$statusKey] ?? $booking->status, false],
        ['نوع خدمت', $serviceLabel, false],
        ['نحوه انجام', $modeLabel, false],
        ['نوع آموزش', $trainingLabel, false],
        ['شهر', $cityName, false],
        ['سگ', $booking->dog->title ?? null, false],
        ['نژاد', $breedName, false],
        ['تاریخ ترجیحی', $preferredJalali, true],
        ['ساعت ترجیحی', $booking->preferred_slot ?? null, true],
        ['زمان قرار', $appointmentJalali, true],
        ['بازه', $booking->slot ?? null, true],
        ['قیمت پایه', isset($booking->price) ? number_format((float) $booking->price).' ت' : null, true],
        ['مالیات', isset($booking->tax) ? number_format((float) $booking->tax).' ت' : null, true],
        ['تخفیف', isset($booking->discount) ? number_format((float) $booking->discount).' ت' : null, true],
        ['جمع', number_format((float) ($booking->total ?? $booking->price ?? 0)).' ت', true],
        ['روش پرداخت', $booking->payment_method ?? null, false],
        ['وضعیت پرداخت', $booking->payment_status ?? null, false],
    ];
    if ($showContact) {
        $rows[] = ['نام تماس', $booking->contact_name ?? null, false];
        $rows[] = ['تلفن تماس', $booking->contact_phone ?? null, true];
    }
    if ($booking->provider) {
        $providerName = trim(($booking->provider->name ?? '').' '.($booking->provider->family ?? ''));
        $rows[] = ['ارائه‌دهنده', $providerName !== '' ? $providerName : ($booking->provider->name ?? null), false];
    }
    if ($booking->user && empty($hideCustomer)) {
        $customerName = trim(($booking->user->name ?? '').' '.($booking->user->family ?? ''));
        $rows[] = ['مشتری', $customerName !== '' ? $customerName : ($booking->user->name ?? null), false];
    }
    $rows[] = ['یادداشت', $booking->notes ?? null, false];
@endphp

<section class="sp-dash__panel sp-booking-details">
    <header class="sp-dash__panel-head">
        <h2>جزئیات کامل رزرو</h2>
        <span class="sp-dash__chip">{{ $statusLabels[$statusKey] ?? $statusKey }}</span>
    </header>
    <div class="sp-booking-details__grid">
        @foreach($rows as [$label, $value, $isNum])
            @continue($value === null || $value === '')
            <div class="sp-booking-details__item">
                <span class="sp-booking-details__label">{{ $label }}</span>
                <strong class="{{ $isNum ? 'num' : '' }}">{{ $value }}</strong>
            </div>
        @endforeach
    </div>
    @if($booking->dog && $booking->dog->image)
        <div class="sp-booking-details__media">
            <img src="{{ asset($booking->dog->image) }}" alt="{{ $booking->dog->title }}">
        </div>
    @endif
</section>