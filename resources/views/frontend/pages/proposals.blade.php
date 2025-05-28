@extends('frontend.layouts.header-dashboard')

@section('content')
    <!-- Progress Bar -->
    <div class="container" style="max-width: 600px; margin-top: 30px;">
        <div class="progress" style="height: 12px; background-color: #e3f2fd; border-radius: 10px; overflow: hidden;">
            <div class="progress-bar" role="progressbar"
                 style="width: 80%; background-color: #0d47a1;"
                 aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <!-- Proposals Section -->
    <div class="proposals" style="max-width: 600px; margin: 40px auto; padding: 20px; direction: rtl;">
        <h1 style="text-align: center; margin-bottom: 30px;">پیشنهادات</h1>

        <!-- Profile Image and Description -->
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('images/support-card.png') }}" alt="تصویر کاربر"
                 style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <p style="margin-top: 15px; font-size: 16px; color: #333;">توضیح کوتاه در مورد تصویر یا کاربر در اینجا قرار می‌گیرد.</p>
        </div>

        <!-- Proposals List -->
        @foreach ([1, 2, 3] as $i)
            <div class="proposal-item"
                 style="border: 1px solid #d0d0d0; border-radius: 12px; padding: 20px; margin-bottom: 25px; background-color: #fefefe; box-shadow: 0 1px 5px rgba(0,0,0,0.05);">
                <h3 style="margin-top: 0;">پیشنهاد {{ $i }}</h3>
                <p>جزئیات پیشنهاد {{ $i }}...</p>
                <button style="padding: 10px 20px; background-color: #0d47a1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                    قبول مربی
                </button>
            </div>
        @endforeach
    </div>
@endsection
