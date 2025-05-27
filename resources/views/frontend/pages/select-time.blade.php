@extends('frontend.layouts.header-dashboard')

@section('content')
    <div class="select-time">
        <h1>انتخاب زمان</h1>
        <div class="time-options">
            <label><input type="radio" name="time" value="immediate"> فوری (نیاز به شروع در اسرع وقت)</label>
            <label><input type="radio" name="time" value="next-week"> هفته آینده</label>
            <label><input type="radio" name="time" value="custom-date"> تاریخ خاص (لطفا تاریخ را وارد کنید)</label>
        </div>
        <button>بعدی</button>
    </div>
@endsection
