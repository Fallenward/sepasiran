@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <div class="container mt-4">
        <div class="col-12 col-md-6 text-center">
            <h2>{{$title}}</h2>
            <x-title-divider></x-title-divider>

            @if($status == 'success')
                <i class="fa fa-check-circle fa-4x text-success"></i>
                <div class="mt-4">
                    <h4 class="text-success">پرداخت با موفقیت انجام شد</h4>
                    <p>پرداخت شما با موفقیت ثبت شد. سفارش شما در حال پردازش است و به زودی ارسال خواهد شد.</p>
                    <a href="{{ route('admin.dashboard.index') }}" class="btn btn-success">مشاهده سفارشات</a>
                </div>
            @else
                <i class="fa fa-times-circle fa-4x text-danger"></i>
                <div class="mt-4">
                    <h4 class="text-danger">پرداخت ناموفق بود</h4>
                    <p>متاسفانه پرداخت شما با شکست مواجه شد. لطفاً دوباره تلاش کنید یا با پشتیبانی تماس بگیرید.</p>
                    <a href="{{ route('frontend.cart') }}" class="btn btn-primary">بازگشت به سبد خرید</a>
                </div>
            @endif
        </div>
    </div>
@endsection
