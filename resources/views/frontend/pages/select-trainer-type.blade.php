@extends('frontend.layouts.header-dashboard')

@section('content')
<div class="select-trainer-type" style="max-width: 600px; margin: 40px auto; padding: 20px; direction: rtl;">

    <!-- Progress Bar -->
    <div class="progress mb-4" style="height: 8px; background-color: #e0e0e0; border-radius: 10px; overflow: hidden;">
        <div class="progress-bar" role="progressbar" style="width: 50%; background-color: #0d47a1;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <h1 style="text-align: center; margin-bottom: 15px;">انتخاب نوع آموزش</h1>

    <!-- Profile Image -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/support-card.png') }}" alt="تصویر کاربر"
             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <p class="mt-3 text-muted">توضیح کوتاه در مورد تصویر یا کاربر در اینجا قرار می‌گیرد.</p>
    </div>

    <p style="text-align: center; margin-bottom: 30px; color: #555;">لطفا نوع آموزش مورد نیاز را انتخاب کنید.</p>

    <div class="trainer-options" style="margin-bottom: 20px;">
        @foreach ([
            'beginner' => 'آموزش مقدماتی',
            'advanced' => 'تربیت پیشرفته',
            'behavioral' => 'اصلاح رفتار',
            'care' => 'آموزش نگهداری',
            'other' => 'سایر موارد',
        ] as $value => $label)
        <label style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #0d47a1;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
            background-color: #fff;
        ">
            <span>{{ $label }}</span>
            <input type="radio" name="trainer" value="{{ $value }}">
        </label>
        @endforeach
    </div>

    <button type="submit" style="
        background-color: #0d47a1;
        border: none;
        color: white;
        padding: 10px 0;
        border-radius: 8px;
        font-size: 16px;
        max-width: 600px;
        width: 100%;
        cursor: pointer;
        display: block;
        margin: 0 auto;
    ">بعدی</button>
</div>
@endsection
