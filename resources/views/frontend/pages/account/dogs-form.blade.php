@extends('frontend.layouts.app')

@section('title', ($mode === 'edit' ? 'ویرایش سگ' : 'افزودن سگ') . ' — سپاسیران')

@section('content')
@php
    $months = [1=>'فروردین',2=>'اردیبهشت',3=>'خرداد',4=>'تیر',5=>'مرداد',6=>'شهریور',7=>'مهر',8=>'آبان',9=>'آذر',10=>'دی',11=>'بهمن',12=>'اسفند'];
@endphp
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
        @include('frontend.partials.dashboard-nav')

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar" aria-hidden="true"><i class="fas fa-{{ $mode === 'edit' ? 'pen' : 'plus' }}"></i></div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">{{ $mode === 'edit' ? 'ویرایش سگ' : 'افزودن سگ' }}</h1>
                    <p class="sp-dash__lede">مشخصات، تاریخ تولد شمسی، و تصاویر سگ را ثبت کنید.</p>
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

        <form class="panel sp-dash__panel" method="POST" enctype="multipart/form-data"
              action="{{ $mode === 'edit' ? route('account.dogs.update', $dog) : route('account.dogs.store') }}">
            @csrf
            @if($mode === 'edit')
                @method('PUT')
            @endif

            <div class="field">
                <label for="title">نام سگ</label>
                <input id="title" name="title" type="text" value="{{ old('title', $dog->title) }}" required>
                @error('title')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label for="breed_id">نژاد</label>
                <select id="breed_id" name="breed_id">
                    <option value="">— انتخاب کنید —</option>
                    @foreach($breeds as $breed)
                        <option value="{{ $breed->id }}" @selected(old('breed_id', $dog->breed_id) == $breed->id)>{{ $breed->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="color">رنگ</label>
                <select id="color" name="color">
                    <option value="">— انتخاب از پایگاه‌داده —</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->name }}" @selected(old('color', $dog->color) === $color->name)>{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="eye_color">رنگ چشم</label>
                <select id="eye_color" name="eye_color">
                    <option value="">— انتخاب از پایگاه‌داده —</option>
                    @foreach($eyeColors as $eye)
                        <option value="{{ $eye->name }}" @selected(old('eye_color', $dog->eye_color) === $eye->name)>{{ $eye->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="cover_type">نوع پوشش</label>
                <select id="cover_type" name="cover_type">
                    <option value="">— انتخاب از پایگاه‌داده —</option>
                    @foreach($coverTypes as $cover)
                        <option value="{{ $cover->name }}" @selected(old('cover_type', $dog->cover_type) === $cover->name)>{{ $cover->name }}</option>
                    @endforeach
                </select>
            </div>

            <fieldset class="sp-dash__fieldset">
                <legend>تاریخ تولد (تقویم شمسی)</legend>
                <p class="sp-dash__muted">با انتخاب سال/ماه/روز، سن به‌صورت خودکار بر حسب ماه محاسبه می‌شود.</p>
                <div class="sp-dash__date-grid">
                    <div class="field">
                        <label for="birth_year">سال</label>
                        <select id="birth_year" name="birth_year">
                            <option value="">—</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" @selected((string) old('birth_year', $jalaliYear) === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="birth_month">ماه</label>
                        <select id="birth_month" name="birth_month">
                            <option value="">—</option>
                            @foreach($months as $num => $label)
                                <option value="{{ $num }}" @selected((string) old('birth_month', $jalaliMonth) === (string) $num)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="birth_day">روز</label>
                        <select id="birth_day" name="birth_day">
                            <option value="">—</option>
                            @for($d = 1; $d <= 31; $d++)
                                <option value="{{ $d }}" @selected((string) old('birth_day', $jalaliDay) === (string) $d)>{{ $d }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                @error('birth_day')<p class="auth-alert">{{ $message }}</p>@enderror
                @if($dog->age)
                    <p class="sp-dash__muted">سن فعلی ثبت‌شده: <span class="num">{{ $dog->age }}</span> ماه</p>
                @endif
            </fieldset>

            <div class="field">
                <label for="images">تصاویر سگ</label>
                <input id="images" name="images[]" type="file" accept="image/*" multiple>
                <p class="sp-dash__muted">می‌توانید چند تصویر اضافه کنید (حداکثر ۸ فایل، هر کدام تا ۴ مگابایت).</p>
                @error('images')<p class="auth-alert">{{ $message }}</p>@enderror
                @error('images.*')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>

            @if($mode === 'edit' && $dog->media->count())
                <div class="sp-dash__media-grid">
                    @foreach($dog->media as $media)
                        <label class="sp-dash__media-card">
                            <img src="{{ $media->publicUrl() }}" alt="">
                            <span>
                                <input type="checkbox" name="remove_media[]" value="{{ $media->id }}">
                                حذف این تصویر
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="field">
                <label class="sp-dash__check">
                    <input type="checkbox" name="shajare" value="1" @checked(old('shajare', $dog->shajare))>
                    <span>
                        <strong>شجره‌نامه دارد</strong>
                        <small class="sp-dash__muted">اگر سگ دارای سند شجره‌نامه رسمی است این گزینه را فعال کنید.</small>
                    </span>
                </label>
            </div>

            <div class="sp-dash__form-actions">
                <button class="sp-btn sp-btn--primary" type="submit">{{ $mode === 'edit' ? 'ذخیره تغییرات' : 'ثبت سگ' }}</button>
                <a class="sp-btn sp-btn--ghost" href="{{ route('account.dogs.index') }}">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection