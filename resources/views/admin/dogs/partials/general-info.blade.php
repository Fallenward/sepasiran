<div class="row gy-3">
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">مشخصات اصلی</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>نام</dt><dd>{{ $dog->title ?: ($dog->name ?? '—') }}</dd>
                <dt>نژاد</dt><dd>{{ $dog->breed->name ?? ($dog->breedName() ?? '—') }}</dd>
                <dt>شهر</dt><dd>{{ $dog->city->name ?? '—' }}</dd>
                <dt>وضعیت</dt><dd><span class="sp-admin-status">{{ $dog->status ?: '—' }}</span></dd>
                <dt>قیمت</dt><dd class="num">{{ $dog->price !== null ? number_format((int) $dog->price).' ریال' : '—' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">ظاهر و بدن</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>رنگ</dt><dd>{{ $dog->color ?: '—' }}</dd>
                <dt>سن</dt><dd class="num">{{ $dog->age !== null ? $dog->age.' ماه' : '—' }}</dd>
                <dt>اندازه</dt><dd>{{ $dog->size ?: '—' }}</dd>
                <dt>نوع پوشش</dt><dd>{{ $dog->cover_type ?: '—' }}</dd>
                <dt>رنگ چشم</dt><dd>{{ $dog->eye_color ?: '—' }}</dd>
                <dt>شجره‌نامه</dt><dd>{{ $dog->shajare ? 'بله' : 'خیر' }}</dd>
            </dl>
        </div>
    </div>
    <div class="col-12">
        <div class="sp-admin-panel">
            <h3 class="h6 mb-3">مالکیت و ثبت</h3>
            <dl class="sp-admin-kv mb-0">
                <dt>مالک</dt>
                <dd>
                    {{ $dog->owner->name ?? ('#'.($dog->user_id ?: '—')) }}
                    @if($dog->owner?->email)
                        <div class="text-muted small">{{ $dog->owner->email }} · <span class="num">{{ $dog->owner->phone ?: '—' }}</span></div>
                    @endif
                </dd>
                <dt>تولد</dt><dd class="num">{{ optional($dog->birth_date)->format('Y/m/d') ?: '—' }}</dd>
                <dt>به‌روزرسانی</dt><dd class="num">{{ optional($dog->updated_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') ?: '—' }}</dd>
            </dl>
            @if($dog->description)
                <div class="mt-3 pt-3" style="border-top:1px solid var(--sp-admin-border)">
                    <strong>توضیحات کامل</strong>
                    <p class="mb-0 mt-2" style="white-space:pre-wrap">{{ $dog->description }}</p>
                </div>
            @endif
        </div>
    </div>
</div>