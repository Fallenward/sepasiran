@extends('frontend.layouts.header-dashboard')

@section('content')
<div class="select-breed" style="max-width: 600px; margin: 40px auto; padding: 20px; font-family: Arial, sans-serif; direction: rtl;">

    <!-- Progress Bar -->
    <div class="progress mb-4" style="height: 8px; background-color: #e0e0e0; border-radius: 10px; overflow: hidden;">
        <div class="progress-bar" role="progressbar" style="width: 25%; background-color: #0d47a1;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <h1 style="text-align: center; margin-bottom: 15px;">انتخاب نژاد سگ</h1>

    <!-- Profile Image -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/support-card.png') }}" alt="تصویر کاربر"
             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <p class="mt-3 text-muted">توضیح کوتاه در مورد تصویر یا کاربر در اینجا قرار می‌گیرد.</p>
    </div>

    <p style="text-align: center; margin-bottom: 30px; color: #555;">برای معرفی بهترین مربیان سگ، لطفا نژاد سگ خود را مشخص کنید.</p>

    <select style="
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #0d47a1;
        border-radius: 8px;
        background-color: white;
        color: #333;
        font-size: 16px;
        margin-bottom: 20px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
    ">
        <option value="german-shepherd">ژرمن شپرد</option>
        <option value="husky">هاسکی</option>
        <option value="doberman">دوبرمن</option>
        <option value="other">سایر موارد</option>
    </select>

    <button type="submit" style="
        background-color: #0d47a1;
        border: none;
        color: white;
        padding: 10px 0;
        border-radius: 8px;
        font-size: 16px;
        max-width: 500px;
        width: 100%;
        cursor: pointer;
        display: block;
        margin: 0 auto;
    ">بعدی</button>
</div>
@endsection
