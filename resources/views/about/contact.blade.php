@extends('layouts.app')

@section('content')

<x-page-header
    title="تماس با ما"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'مربیان', 'route' => route('frontend.coaches.index')],
    ]"
    bgColor="linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)"
/>
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <!-- اطلاعات تماس و تصویر -->
            <div class="col-md-6 text-center">
                <img src="/path-to-your-image.svg" alt="تماس با ما" class="img-fluid mb-4" style="max-height: 250px;">
                <div class="text-muted">
                    <p>آدرس دفتر: تهران، خیابان مثال</p>
                    <p>تلفن: 09111111111</p>
                    <p>ایمیل: example@gmail.com</p>
                </div>
            </div>

            <!-- فرم تماس -->
            <div class="col-md-6">
                <form method="POST" action="{{ route('frontend.contact.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label float-end">نام و نام خانوادگی</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">ایمیل</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">شماره تماس</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">پیام شما</label>
                        <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror"></textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">ارسال</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
