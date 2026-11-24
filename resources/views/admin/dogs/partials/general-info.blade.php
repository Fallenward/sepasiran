<div class="row">
    <div class="col-md-8">
        <dl class="sp-admin-kv">
            <dt>نام</dt><dd>{{ $dog->title ?: ($dog->name ?? '—') }}</dd>
            <dt>نژاد</dt><dd>{{ $dog->breed->name ?? ($dog->breedName() ?? '—') }}</dd>
            <dt>شهر</dt><dd>{{ $dog->city->name ?? '—' }}</dd>
            <dt>وضعیت</dt><dd><span class="sp-admin-status">{{ $dog->status ?: '—' }}</span></dd>
            <dt>رنگ</dt><dd>{{ $dog->color ?: '—' }}</dd>
            <dt>سن</dt><dd class="num">{{ $dog->age !== null ? $dog->age.' ماه' : '—' }}</dd>
            <dt>اندازه</dt><dd>{{ $dog->size ?: '—' }}</dd>
            <dt>نوع پوشش</dt><dd>{{ $dog->cover_type ?: '—' }}</dd>
            <dt>رنگ چشم</dt><dd>{{ $dog->eye_color ?: '—' }}</dd>
            <dt>قیمت</dt><dd class="num">{{ $dog->price !== null ? number_format((int) $dog->price) : '—' }}</dd>
            <dt>شجره‌نامه</dt><dd>{{ $dog->shajare ? 'بله' : 'خیر' }}</dd>
            <dt>مالک</dt><dd>{{ $dog->owner->name ?? ('#'.($dog->user_id ?: '—')) }}</dd>
        </dl>
        @if($dog->description)
            <div class="mt-3">
                <strong>توضیحات</strong>
                <p class="mb-0 mt-1">{{ $dog->description }}</p>
            </div>
        @endif
    </div>
    <div class="col-md-4">
        @php
            $img = $dog->primaryImageUrl() ?? null;
            if (!$img && $dog->image) {
                $raw = $dog->image;
                $img = str_starts_with($raw, 'http') ? $raw : (str_starts_with($raw, 'images/') ? asset($raw) : asset('storage/'.ltrim($raw,'/')));
            }
        @endphp
        @if($img)
            <img src="{{ $img }}" alt="{{ $dog->title }}" class="img-fluid rounded border" style="width:100%;object-fit:cover;max-height:280px">
        @endif
    </div>
</div>