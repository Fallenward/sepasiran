@extends('admin.layout')

@section('title', 'رزرو '.($booking->booking_code ?? $booking->id))

@section('content')
@php
    $customer = trim(($booking->user->name ?? '').' '.($booking->user->family ?? ''));
    $provider = trim(($booking->provider->name ?? '').' '.($booking->provider->family ?? ''));
@endphp
@include('admin.components.page-header', [
    'title' => 'جزئیات رزرو',
    'subtitle' => $booking->booking_code ?: ('#'.$booking->id),
])

<div class="sp-admin-pagehead__actions mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.bookings.index') }}">بازگشت</a>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.bookings.edit', $booking) }}">ویرایش</a>
</div>

<div class="row gy-3">
    <div class="col-lg-7">
        <div class="card sp-admin-card">
            <div class="card-header"><strong>اطلاعات رزرو</strong></div>
            <div class="card-body">
                <dl class="sp-admin-kv">
                    <dt>کد</dt><dd class="num">{{ $booking->booking_code ?: '—' }}</dd>
                    <dt>خدمت</dt><dd>{{ $booking->service_type ?: '—' }}</dd>
                    <dt>حالت</dt><dd>{{ $booking->fulfillment_mode ?: '—' }}</dd>
                    <dt>وضعیت</dt><dd><span class="sp-admin-status">{{ $booking->status ?: '—' }}</span></dd>
                    <dt>زمان جلسه</dt>
                    <dd class="num">
                        {{ $appointmentJalali ?? (optional($booking->appointment_date)->format('Y-m-d H:i') ?: '—') }}
                        {{ $booking->slot ? ' · '.$booking->slot : '' }}
                    </dd>
                    <dt>تاریخ ترجیحی</dt>
                    <dd class="num">{{ optional($booking->preferred_date)->format('Y-m-d') ?: '—' }} {{ $booking->preferred_slot }}</dd>
                    <dt>نژاد</dt><dd>{{ $booking->breed->name ?? '—' }}</dd>
                    <dt>شهر</dt><dd>{{ $booking->city->name ?? '—' }}</dd>
                    <dt>یادداشت</dt><dd>{{ $booking->notes ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card sp-admin-card mb-3">
            <div class="card-header"><strong>افراد</strong></div>
            <div class="card-body">
                <dl class="sp-admin-kv">
                    <dt>مشتری</dt>
                    <dd>
                        {{ $customer !== '' ? $customer : '—' }}
                        <div class="text-muted small">{{ $booking->user->email ?? '' }} · <span class="num">{{ $booking->user->phone ?? $booking->contact_phone ?? '—' }}</span></div>
                    </dd>
                    <dt>ارائه‌دهنده</dt>
                    <dd>
                        {{ $provider !== '' ? $provider : '—' }}
                        <div class="text-muted small">{{ $booking->provider->email ?? '' }}</div>
                    </dd>
                    <dt>تماس ثبت‌شده</dt>
                    <dd>{{ $booking->contact_name ?: '—' }} · <span class="num">{{ $booking->contact_phone ?: '—' }}</span></dd>
                </dl>
            </div>
        </div>
        <div class="card sp-admin-card">
            <div class="card-header"><strong>پرداخت</strong></div>
            <div class="card-body">
                <dl class="sp-admin-kv">
                    <dt>مبلغ</dt><dd class="num">{{ number_format((float) ($booking->price ?? 0)) }}</dd>
                    <dt>تخفیف</dt><dd class="num">{{ number_format((float) ($booking->discount ?? 0)) }}</dd>
                    <dt>مالیات</dt><dd class="num">{{ number_format((float) ($booking->tax ?? 0)) }}</dd>
                    <dt>جمع</dt><dd class="num"><strong>{{ number_format((float) ($booking->total ?? 0)) }}</strong></dd>
                    <dt>وضعیت پرداخت</dt><dd>{{ $booking->payment_status ?: '—' }}</dd>
                    <dt>تسویه</dt><dd>{{ $booking->settlement_status ?: '—' }}</dd>
                    <dt>روش</dt><dd>{{ $booking->payment_method ?: '—' }}</dd>
                    <dt>تراکنش</dt><dd class="num">{{ $booking->transaction_id ?: $booking->gateway_transaction_id ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection