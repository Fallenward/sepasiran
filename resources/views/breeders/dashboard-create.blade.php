@extends('frontend.layouts.app')

@section('title', 'افزودن سگ — سپاسیران')

@section('content')
<div class="sp-dash">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
<section class="sp-dash__hero">
            <div class="sp-dash__hero-copy">
                <p class="sp-dash__eyebrow">پرورش‌دهنده</p>
                <h1 class="sp-dash__title">افزودن سگ</h1>
                <p class="sp-dash__lede">مشخصات اصلی را وارد کنید؛ شجره اختیاری است.</p>
            </div>
            <a class="sp-btn sp-btn--ghost" href="{{ route('breeder.dashboard') }}">بازگشت به داشبورد</a>
        </section>

        <form class="panel sp-dash__panel" action="{{ route('breeder.dashboard-store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="title">نام سگ</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="field">
                <label for="color">رنگ</label>
                <input id="color" type="text" name="color" value="{{ old('color') }}">
            </div>
            <div class="field">
                <label for="status">وضعیت</label>
                <select id="status" name="status">
                    <option value="ready">آماده</option>
                    <option value="pending">در انتظار</option>
                    <option value="sold">فروخته شده</option>
                    <option value="waiting">منتظر</option>
                </select>
            </div>
            <div class="field">
                <label for="age">سن</label>
                <input id="age" type="number" name="age" value="{{ old('age') }}">
            </div>
            <div class="field">
                <label for="birth_date">تاریخ تولد</label>
                <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date') }}">
            </div>
            <div class="field">
                <label for="cover_type">نوع پوشش</label>
                <input id="cover_type" type="text" name="cover_type" value="{{ old('cover_type') }}">
            </div>
            <div class="field">
                <label for="eye_color">رنگ چشم</label>
                <input id="eye_color" type="text" name="eye_color" value="{{ old('eye_color') }}">
            </div>
            <div class="field">
                <label><input type="checkbox" name="shajare" value="1" @checked(old('shajare'))> دارای شجره‌نامه</label>
            </div>
            <div class="field">
                <label for="breed_id">نژاد</label>
                <select id="breed_id" name="breed_id">
                <option value="">— انتخاب کنید —</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach

                </select>
            </div>

            <h2 class="sp-dash__panel-head" style="margin-top:12px">اطلاعات والدین</h2>
            <div class="field">
                <label for="father_name">نام پدر</label>
                <input id="father_name" type="text" name="father_name">
            </div>
            <div class="field">
                <label for="father_breed_id">نژاد پدر</label>
                <select id="father_breed_id" name="father_breed_id">
                <option value="">— انتخاب کنید —</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach

                </select>
            </div>
            <div class="field">
                <label for="mother_name">نام مادر</label>
                <input id="mother_name" type="text" name="mother_name">
            </div>
            <div class="field">
                <label for="mother_breed_id">نژاد مادر</label>
                <select id="mother_breed_id" name="mother_breed_id">
                <option value="">— انتخاب کنید —</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach

                </select>
            </div>

            <button type="submit" class="sp-btn sp-btn--primary">ثبت سگ</button>
        </form>
    </div>
</div>
@endsection
