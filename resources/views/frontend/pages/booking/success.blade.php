@extends('layouts.app')

@section('title', 'رزرو ثبت شد')

@section('content')
<x-listing-hero
    title="رزرو ثبت شد"
    lede="درخواست شما با وضعیت در انتظار تأیید ذخیره شد."
    icon="trust"
    image="images/demo/hero/hero-01.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'رزرو خدمت', 'route' => route('frontend.booking.index')],
        ['label' => 'تأیید'],
    ]"
/>

<div class="wrap" style="padding:28px 0 56px;max-width:720px">
    <div class="panel">
        <h2 style="margin-top:0">جزئیات رزرو #{{ $booking->id }}</h2>
        <div class="list-row"><span>خدمت</span><strong>{{ $serviceLabels[$booking->service_type] ?? $booking->service_type }}</strong></div>
        <div class="list-row"><span>ارائه‌دهنده</span><strong>{{ $booking->provider->name ?? '—' }}</strong></div>
        <div class="list-row"><span>زمان</span><strong class="num">{{ optional($booking->appointment_date)->format('Y-m-d H:i') }}</strong></div>
        <div class="list-row"><span>مبلغ</span><strong class="num">{{ number_format((float) ($booking->total ?? $booking->price)) }} تومان</strong></div>
        <div class="list-row"><span>وضعیت</span><strong>{{ $booking->status }}</strong></div>
        @if($booking->notes)
            <p style="margin-top:16px;color:var(--sp-text-muted);white-space:pre-line">{{ $booking->notes }}</p>
        @endif
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px">
            <a class="btn btn-primary" href="{{ route('frontend.booking.index') }}">رزرو جدید</a>
            <a class="btn btn-ghost" href="{{ route('frontend.home') }}">بازگشت به خانه</a>
        </div>
    </div>
</div>
@endsection