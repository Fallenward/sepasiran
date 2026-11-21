@extends('layouts.app')

@section('title', 'خدمات من')

@section('content')
<div class="wrap" style="padding:32px 0 64px;max-width:900px">
    <div class="panel">
        <h1 style="margin:0 0 8px;font-size:1.4rem">خدمات رزرو من</h1>
        <p class="sp-booking__hint">هر خدمت قیمت، مدت و تخصص جدا دارد و در رزرو مستقیم نمایش داده می‌شود.</p>
        @if(session('success'))<p style="color:var(--sp-green)">{{ session('success') }}</p>@endif
        @if($errors->any())
            <ul style="color:#f2b4b4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        @endif

        <form method="post" action="{{ route('account.services.store') }}" style="display:grid;gap:12px;margin:18px 0">
            @csrf
            <div class="field">
                <label>نوع خدمت</label>
                <select name="service_type" required>
                    @foreach($catalog as $row)
                        <option value="{{ $row['key'] }}">{{ $row['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>نام خدمت</label><input name="name" required maxlength="120" placeholder="مثلاً جلسه خصوصی"></div>
            <div class="field"><label>توضیح</label><textarea name="description" rows="2"></textarea></div>
            <div class="field">
                <label>تخصص</label>
                <select name="specialty_id">
                    <option value="">—</option>
                    @foreach($specialties as $sp)
                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field"><label>قیمت پایه (تومان)</label><input class="num" type="number" name="base_price" min="0" step="1000" required></div>
            <div class="field"><label>مدت (دقیقه)</label><input class="num" type="number" name="duration_minutes" min="15" max="480" value="60" required></div>
            <label><input type="checkbox" name="is_active" value="1" checked> فعال</label>
            @if($role === 'coach')
                <strong>هزینه نوع آموزش</strong>
                @foreach($trainingTypes as $t)
                    <div class="field"><label>{{ $t['label'] }}</label>
                        <input class="num" type="number" min="0" name="training_surcharges[{{ $t['key'] }}]" value="{{ $t['surcharge'] }}">
                    </div>
                @endforeach
            @endif
            <button class="btn btn-primary" type="submit">افزودن خدمت</button>
        </form>

        <div style="display:grid;gap:12px;margin-top:24px">
            @forelse($services as $service)
                <div class="sp-booking__locked-card" style="margin:0">
                    <form method="post" action="{{ route('account.services.update', $service) }}" style="display:grid;gap:8px">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $service->name }}" required>
                        <textarea name="description" rows="2">{{ $service->description }}</textarea>
                        <div class="field"><label>قیمت</label><input class="num" type="number" name="base_price" value="{{ $service->base_price }}" required></div>
                        <div class="field"><label>مدت</label><input class="num" type="number" name="duration_minutes" value="{{ $service->duration_minutes }}" required></div>
                        <label><input type="checkbox" name="is_active" value="1" @checked($service->is_active)> فعال</label>
                        <button class="btn btn-primary" type="submit">ذخیره</button>
                    </form>
                    <form method="post" action="{{ route('account.services.destroy', $service) }}" onsubmit="return confirm('حذف شود؟')" style="margin-top:8px">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost" type="submit">حذف</button>
                    </form>
                </div>
            @empty
                <p class="sp-booking__hint">هنوز خدمتی ثبت نشده.</p>
            @endforelse
        </div>
        <p style="margin-top:16px"><a href="{{ route('account.service-prices.edit') }}">قیمت‌های ساده (سازگاری)</a>
            · <a href="{{ route('account.availability.edit') }}">زمان‌بندی</a></p>
    </div>
</div>
@endsection