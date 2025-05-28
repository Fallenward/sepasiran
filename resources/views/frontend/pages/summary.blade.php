@extends('frontend.layouts.header-dashboard')

@section('content')
<div class="summary-modern-wrapper py-5" style="direction: rtl;>

    <!-- Progress Bar -->
    <div class="container mb-4" style="max-width: 720px;">
        <div class="progress" style="height: 8px; background-color: #e0e0e0; border-radius: 10px; overflow: hidden;">
            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #0d47a1;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="container">
        <div class="card border-0 shadow-lg p-4 rounded-4 mx-auto" style="max-width: 720px; background: linear-gradient(135deg, #ffffff, #f8f9fa);">
            <div class="text-center mb-4">
                <h1 class="fw-bold mb-2" style="color: #0d47a1; font-size: 2rem;">نتیجه انتخاب شما</h1>
                <div class="text-center mb-4">
                    <img src="{{ asset('images/support-card.png') }}" alt="تصویر کاربر"
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <p class="mt-3 text-muted">توضیح کوتاه در مورد تصویر یا کاربر در اینجا قرار می‌گیرد.</p>
                </div>
                <p class="text-muted">با پاسخ به چند سوال، بهترین مربیان سگ را برایتان یافته‌ایم</p>
            </div>

            <div class="info-box bg-white p-4 rounded-4 shadow-sm mb-4 border border-light-subtle">
                <div class="row gy-3">
                    <div class="col-12 d-flex justify-content-between">
                        <span class="fw-bold text-secondary">🐾 مربی انتخابی:</span>
                        <span class="text-dark">بنجامین رضایی</span>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <span class="fw-bold text-secondary">🐕 نژاد سگ:</span>
                        <span class="text-dark">ژرمن شپرد</span>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <span class="fw-bold text-secondary">🎓 نوع آموزش:</span>
                        <span class="text-dark">آموزش مقدماتی</span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-lg px-5 py-3 rounded-pill text-white"
                        style="background-color: #0d47a1; font-weight: bold; font-size: 1rem; box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3); transition: all 0.3s;"
                        onmouseover="this.style.backgroundColor='#08306b'"
                        onmouseout="this.style.backgroundColor='#0d47

                        تماس با مربی
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
