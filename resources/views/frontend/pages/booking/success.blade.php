@extends('layouts.app')

@section('title', 'رزرو ثبت شد')

@section('content')
@php
    $code = $booking['booking_code'] ?? '';
    $faCode = preg_replace_callback('/\d/', fn ($m) => ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'][(int)$m[0]], $code);
    $serviceLabel = $serviceLabels[$booking['service_type'] ?? ''] ?? ($booking['service_type'] ?? '—');
@endphp
<div class="wrap" style="padding:40px 0 64px;max-width:720px">
    <div class="panel" style="text-align:center">
        <div class="sp-section-head__icon" style="margin:0 auto 16px;width:56px;height:56px;border-radius:16px">
            <x-sp-icon name="trust" />
        </div>
        <h1 style="margin:0 0 8px;font-size:1.6rem">رزرو ثبت شد</h1>
        <p style="color:var(--sp-text-muted);margin:0 0 18px">کد رزرو شما:</p>
        <p class="num" style="font-size:1.8rem;font-weight:800;color:var(--sp-green);margin:0 0 24px">{{ $faCode }}</p>

        <div style="text-align:right">
            <div class="list-row"><span>خدمت</span><strong>{{ $serviceLabel }}</strong></div>
            <div class="list-row"><span>ارائه‌دهنده</span><strong>{{ $booking['provider']['display_name'] ?? '—' }}</strong></div>
            @if(!empty($booking['breed']['name']))
                <div class="list-row"><span>نژاد</span><strong>{{ $booking['breed']['name'] }}</strong></div>
            @endif
            <div class="list-row"><span>تاریخ</span><strong class="num">{{ $booking['appointment_jalali'] ?? '—' }}</strong></div>
            <div class="list-row"><span>ساعت</span><strong class="num">{{ $booking['slot'] ?? '—' }}</strong></div>
            <div class="list-row" style="border:0"><span>مبلغ</span><strong class="num">{{ number_format((float) ($booking['total'] ?? 0)) }} تومان</strong></div>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-top:22px">
            <a class="btn btn-primary" href="{{ route('frontend.booking.index') }}">رزرو جدید</a>
            <a class="btn btn-ghost" href="{{ route('frontend.home') }}">خانه</a>
        </div>
    </div>
</div>
@endsection