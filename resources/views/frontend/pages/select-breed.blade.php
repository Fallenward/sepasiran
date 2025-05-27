@extends('frontend.layouts.header-dashboard')

@section('content')
    <div class="select-breed">
        <h1>انتخاب نژاد سگ</h1>
        <p>برای معرفی بهترین مربیان سگ، لطفا نژاد سگ خود را مشخص کنید.</p>
        <select>
            <option value="german-shepherd">ژرمن شپرد</option>
            <option value="husky">هاسکی</option>
            <option value="doberman">دوبرمن</option>
            <option value="other">سایر موارد</option>
        </select>
        <button>بعدی</button>
    </div>
@endsection
