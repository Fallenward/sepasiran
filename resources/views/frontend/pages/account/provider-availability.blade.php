@extends('layouts.app')

@section('title', 'زمان‌بندی دسترسی')

@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:900px">
    <div class="panel">
        <h1 style="margin:0 0 8px;font-size:1.4rem">دسترسی و تقویم</h1>
        @if(session('success'))<p style="color:var(--sp-green)">{{ session('success') }}</p>@endif
        @if($errors->any())
            <ul style="color:#f2b4b4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        @endif

        <form method="post" action="{{ route('account.availability.settings') }}" style="display:grid;gap:10px;margin-bottom:22px">
            @csrf
            <div class="field"><label>حداقل فاصله رزرو (دقیقه)</label>
                <input class="num" type="number" name="min_notice_minutes" value="{{ $settings->min_notice_minutes }}" required></div>
            <div class="field"><label>حداکثر روزهای جلو</label>
                <input class="num" type="number" name="max_advance_days" value="{{ $settings->max_advance_days }}" required></div>
            <div class="field"><label>فاصله اسلات (دقیقه، اختیاری)</label>
                <input class="num" type="number" name="slot_interval_minutes" value="{{ $settings->slot_interval_minutes }}"></div>
            <button class="btn btn-primary" type="submit">ذخیره تنظیمات</button>
        </form>

        <h2 style="font-size:1.1rem">ساعات کاری / استراحت</h2>
        <form method="post" action="{{ route('account.availability.rules.store') }}" style="display:grid;gap:8px;margin:12px 0 18px">
            @csrf
            <select name="weekday" required>
                @foreach($weekdays as $k => $label)
                    <option value="{{ $k }}">{{ $label }}</option>
                @endforeach
            </select>
            <input type="time" name="start_time" required>
            <input type="time" name="end_time" required>
            <label><input type="checkbox" name="is_break" value="1"> استراحت (حذف از اسلات‌ها)</label>
            <button class="btn btn-primary" type="submit">افزودن بازه</button>
        </form>
        <div style="display:grid;gap:8px;margin-bottom:22px">
            @forelse($rules as $rule)
                <div class="list-row">
                    <span>{{ $weekdays[$rule->weekday] ?? $rule->weekday }} · {{ substr((string)$rule->start_time,0,5) }}–{{ substr((string)$rule->end_time,0,5) }}
                        {{ $rule->is_break ? '(استراحت)' : '' }}</span>
                    <form method="post" action="{{ route('account.availability.rules.destroy', $rule) }}">@csrf @method('DELETE')
                        <button class="btn btn-ghost" type="submit">حذف</button></form>
                </div>
            @empty
                <p class="sp-booking__hint">بدون بازه سفارشی، اسلات‌های پیش‌فرض کاتالوگ استفاده می‌شود.</p>
            @endforelse
        </div>

        <h2 style="font-size:1.1rem">مسدودسازی تاریخ</h2>
        <form method="post" action="{{ route('account.availability.exceptions.store') }}" style="display:grid;gap:8px;margin:12px 0">
            @csrf
            <input type="date" name="date" min="{{ now()->toDateString() }}" required>
            <select name="kind"><option value="unavailable">تعطیل</option><option value="block">مسدود</option></select>
            <label><input type="checkbox" name="all_day" value="1" checked> تمام روز</label>
            <input type="time" name="start_time"><input type="time" name="end_time">
            <input name="note" placeholder="یادداشت">
            <button class="btn btn-primary" type="submit">افزودن</button>
        </form>
        <div style="display:grid;gap:8px">
            @foreach($exceptions as $ex)
                <div class="list-row">
                    <span class="num">{{ $ex->date->format('Y-m-d') }} · {{ $ex->kind }} {{ $ex->all_day ? 'تمام‌روز' : '' }}</span>
                    <form method="post" action="{{ route('account.availability.exceptions.destroy', $ex) }}">@csrf @method('DELETE')
                        <button class="btn btn-ghost" type="submit">حذف</button></form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection