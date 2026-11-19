@extends('frontend.layouts.app')

@section('title', 'افزودن سگ — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-plus"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">افزودن سگ</h1>
                    <p class="sp-dash__lede">اطلاعات پایه سگ را وارد کنید تا در رزرو قابل انتخاب باشد.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>بازگشت</strong>
                    <p>به لیست سگ‌های حساب برگردید.</p>
                </div>
                <a class="sp-btn sp-btn--ghost" href="{{ route('account.dogs.index') }}">سگ‌های من</a>
            </aside>
        </section>

        <form class="panel sp-dash__panel" method="POST" action="{{ route('account.dogs.store') }}">
            @csrf
            <div class="field">
                <label for="title">نام سگ</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
                @error('title')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="breed_id">نژاد</label>
                <select id="breed_id" name="breed_id">
                    <option value="">— انتخاب کنید —</option>
                    @foreach($breeds as $breed)
                        <option value="{{ $breed->id }}" @selected(old('breed_id') == $breed->id)>{{ $breed->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="color">رنگ</label>
                <input id="color" name="color" type="text" value="{{ old('color') }}">
            </div>
            <div class="field">
                <label for="age">سن (ماه)</label>
                <input id="age" name="age" type="number" min="0" value="{{ old('age') }}">
            </div>
            <div class="field">
                <label for="birth_date">تاریخ تولد</label>
                <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}">
            </div>
            <div class="field">
                <label for="cover_type">نوع پوشش</label>
                <input id="cover_type" name="cover_type" type="text" value="{{ old('cover_type') }}">
            </div>
            <div class="field">
                <label for="eye_color">رنگ چشم</label>
                <input id="eye_color" name="eye_color" type="text" value="{{ old('eye_color') }}">
            </div>
            <div class="field">
                <label><input type="checkbox" name="shajare" value="1" @checked(old('shajare'))> دارای شجره‌نامه</label>
            </div>
            <div class="sp-dash__form-actions">
                <button class="sp-btn sp-btn--primary" type="submit">ثبت سگ</button>
                <a class="sp-btn sp-btn--ghost" href="{{ route('account.dogs.index') }}">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection
