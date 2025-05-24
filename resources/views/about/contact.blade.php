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
        <img src="{{ asset('images/Contact.png') }}" alt="تماس با ما" class="img-fluid mb-4" style="max-height: 250px;">
        <div class="text-muted">
            <p>آدرس دفتر: تهران، خیابان مثال</p>
            <p>تلفن: 09111111111</p>
            <p>ایمیل: example@gmail.com</p>
        </div>
    </div>

        <!-- فرم تماس -->
        <div class="col-md-6">
            <style>
                .form-wrapper {
                    border-radius: 1rem;
                    padding: 2rem;
                    background-color: #f8f9fa;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                }

                .blue-border {
                    border: 2px solid #007bff !important;
                    border-radius: 0.5rem;
                }

                .blue-border:focus {
                    border-color: #0056b3 !important;
                    box-shadow: none !important;
                }

                .btn-block {
                    display: block;
                    width: 100%;
                    border-radius: 0.5rem;
                }
            </style>

            <div class="form-wrapper">
                <form method="POST" action="{{ route('frontend.contact.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label float-end">نام و نام خانوادگی</label>
                        <input type="text" name="name" class="form-control blue-border @error('name') is-invalid @enderror">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">ایمیل</label>
                        <input type="email" name="email" class="form-control blue-border @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">شماره تماس</label>
                        <input type="text" name="phone" class="form-control blue-border @error('phone') is-invalid @enderror">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label float-end">پیام شما</label>
                        <textarea name="message" rows="4" class="form-control blue-border @error('message') is-invalid @enderror"></textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">ارسال</button>
                </form>
            </div>
        </div>
    </div>
</div>



<style>
.custom-blue-sides {
    border-left: 3px solid #007bff;
    border-right: 3px solid #007bff;
    border-top: 1px solid #ced4da;
    border-bottom: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.blue-border {
    border: 2px solid #007bff !important; /* Bootstrap's primary blue */
    border-radius: 0.375rem; /* Match Bootstrap rounding */
}

.blue-border:focus {
    border-color: #0056b3 !important; /* Darker blue on focus */
    box-shadow: none; /* Optional: remove Bootstrap's default glow */
}



</style>
@endsection
