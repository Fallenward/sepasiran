@extends('frontend.layouts.header-dashboard')

@section('content')
    <div class="select-trainer-type">
        <h1>انتخاب نوع آموزش</h1>
        <p>لطفا نوع آموزش مورد نیاز را انتخاب کنید.</p>
        <div class="trainer-options">
            <label><input type="radio" name="trainer" value="beginner"> آموزش مقدماتی</label>
            <label><input type="radio" name="trainer" value="advanced"> تربیت پیشرفته</label>
            <label><input type="radio" name="trainer" value="behavioral"> اصلاح رفتار</label>
            <label><input type="radio" name="trainer" value="care"> آموزش نگهداری</label>
            <label><input type="radio" name="trainer" value="other"> سایر موارد</label>
        </div>
        <button>بعدی</button>
    </div>
@endsection
