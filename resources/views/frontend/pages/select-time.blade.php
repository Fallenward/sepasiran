@extends('frontend.layouts.header-dashboard')

@section('content')
<div class="container" style="max-width: 600px; margin: 40px auto; font-family: Tahoma, sans-serif; direction: rtl;">

    <!-- 🔵 Progress Bar -->
    <div class="progress mb-4" style="height: 8px; background-color: #e0e0e0; border-radius: 10px; overflow: hidden;">
        <div class="progress-bar" role="progressbar" style="width: 70%; background-color: #0d47a1;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <h1 class="text-center mb-4">انتخاب زمان</h1>

    <!-- Profile Image -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/support-card.png') }}" alt="تصویر کاربر"
             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <p class="mt-3 text-muted">توضیح کوتاه در مورد تصویر یا کاربر در اینجا قرار می‌گیرد.</p>
    </div>

    <!-- Time Selection -->
    <form>
        <div class="form-wrapper p-4 rounded bg-light shadow-sm">
            <div class="radio-option">
                <label class="blue-radio-box">
                    <span>فوری (نیاز به شروع در اسرع وقت)</span>
                    <input type="radio" name="time" value="immediate">
                </label>
            </div>

            <div class="radio-option">
                <label class="blue-radio-box">
                    <span>هفته آینده</span>
                    <input type="radio" name="time" value="next-week">
                </label>
            </div>

            <div class="radio-option">
                <label class="blue-radio-box">
                    <span>تاریخ خاص (لطفا تاریخ را وارد کنید)</span>
                    <input type="radio" name="time" value="custom-date">
                </label>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-sm rounded"
                        style="background-color: #0d47a1; border-color: #0d47a1; width: 100%; color: white;">
                    بعدی
                </button>
            </div>
        </div>
    </form>
</div>

<!-- ✅ CSS Styles -->
<style>
    .blue-radio-box {
        background-color: #fff;
        border: 2px solid #4a84d1;
        padding: 12px 16px;
        border-radius: 0.5rem;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .blue-radio-box input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #0d47a1;
        margin-left: 8px;
    }

    .form-wrapper {
        background-color: #f8f9fa;
        border-radius: 1rem;
    }
</style>
@endsection
