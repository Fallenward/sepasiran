@extends('layouts.app')

@section('title', 'پرداخت رزرو')

@section('content')
@php
    $code = $booking['booking_code'] ?? '';
    $total = (int) round((float) ($booking['total'] ?? 0));
    $serviceLabel = $serviceLabels[$booking['service_type'] ?? ''] ?? ($booking['service_type'] ?? '—');
@endphp
<div class="wrap" style="padding:40px 0 64px;max-width:640px">
    <div class="panel">
        <h1 style="margin:0 0 8px;font-size:1.5rem">پرداخت رزرو</h1>
        <p class="sp-booking__hint" style="margin:0 0 18px">رزرو ثبت شد. مبلغ تا تأیید ارائه‌دهنده به‌صورت امانی نگه داشته می‌شود. در صورت رد یا لغو، بازپرداخت به <strong>کیف پول سپاسیران</strong> واریز می‌شود (نه بازگشت بانکی).</p>

        @if($errors->any())
            <ul style="margin:0 0 14px;padding-right:18px;color:#f2b4b4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div style="text-align:right;margin-bottom:18px">
            <div class="list-row"><span>کد رزرو</span><strong class="num">{{ $code }}</strong></div>
            <div class="list-row"><span>خدمت</span><strong>{{ $serviceLabel }}</strong></div>
            <div class="list-row"><span>ارائه‌دهنده</span><strong>{{ $booking['provider']['display_name'] ?? '—' }}</strong></div>
            <div class="list-row"><span>موجودی کیف پول</span><strong class="num">{{ number_format($walletBalance) }} تومان</strong></div>
            <div class="list-row" style="border:0"><span>مبلغ قابل پرداخت</span><strong class="tile__price num">{{ number_format($total) }} تومان</strong></div>
        </div>

        <div style="display:grid;gap:10px">
            <form method="post" action="{{ route('frontend.booking.pay.process', $code) }}">
                @csrf
                <input type="hidden" name="payment_method" value="wallet">
                <button type="submit" class="btn btn-primary" style="width:100%" {{ $walletBalance < $total ? 'disabled' : '' }}>
                    پرداخت از کیف پول
                </button>
            </form>
            <form method="post" action="{{ route('frontend.booking.pay.process', $code) }}">
                @csrf
                <input type="hidden" name="payment_method" value="gateway">
                <button type="submit" class="btn btn-ghost" style="width:100%">
                    پرداخت از درگاه بانکی
                </button>
            </form>
            <a class="btn btn-ghost" href="{{ route('frontend.booking.success', $code) }}" style="text-align:center">بعداً پرداخت می‌کنم</a>
        </div>
        @if($walletBalance < $total)
            <p class="sp-booking__hint" style="margin-top:12px">موجودی کیف پول کافی نیست. می‌توانید از درگاه بانکی پرداخت کنید یا کیف پول را شارژ کنید.</p>
        @endif
    </div>
</div>
@endsection
