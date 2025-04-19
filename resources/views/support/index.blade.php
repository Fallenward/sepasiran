@extends('layouts.app')

@section('template_title')
    پشتیبانی
@endsection

@section('content')
<div class="container py-5">
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #5a8dee;">
        <h1 class="fw-bold mb-2">پشتیبانی</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.support.index') }}" class="text-white text-decoration-none">پشتیبانی</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold">فرآیند شکایت و رسیدگی به آن:</h5>
        <p class="text-justify">
            شما می‌توانید شکایات خود را در مواردی مانند مشکلات در خرید و فروش، نقض قوانین سایت، مشکلات خدمات پناهگاه‌ها و تأخیر یا مشکلات در خدمات پشتیبانی ثبت کنید و برای بررسی صحیح و دقیق، ارسال مستندات معتبر همچون فاکتور خرید، تصاویر یا ویدئوها، گزارش‌های مکتوب و کد رهگیری ضروری است؛ معمولاً شکایات بین ۳ تا ۷ روز کاری بررسی می‌شوند و در صورت نیاز به زمان بیشتر، تیم پشتیبانی با شما در ارتباط خواهد بود.
        </p>
    </div>

    <form action="#" method="POST" class="row g-4">
        @csrf
        <div class="col-md-6">
            <label class="form-label">شکایت شما مربوط به کدام بخش است</label>
            <input type="text" class="form-control" name="section">
        </div>
        <div class="col-md-6">
            <label class="form-label">نام</label>
            <input type="text" class="form-control" name="first_name">
        </div>
        <div class="col-md-6">
            <label class="form-label">مربی</label>
            <input type="text" class="form-control" name="coach">
        </div>
        <div class="col-md-6">
            <label class="form-label">موضوع</label>
            <input type="text" class="form-control" name="subject">
        </div>
        <div class="col-md-6">
            <label class="form-label">نام خانوادگی</label>
            <input type="text" class="form-control" name="last_name">
        </div>
        <div class="col-md-6">
            <label class="form-label">ایمیل</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="col-md-6">
            <label class="form-label">تلفن تماس</label>
            <input type="text" class="form-control" name="phone">
        </div>
        <div class="col-12">
            <label class="form-label">توضیحات شکایت</label>
            <textarea class="form-control" name="description" rows="4"></textarea>
        </div>
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-danger px-5">ثبت شکایت</button>
        </div>
    </form>
</div>
@endsection
