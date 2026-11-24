@extends('admin.layout')

@section('title', 'سگ: '.($dog->title ?? $dog->id))

@section('content')
@php
    $status = (string) ($dog->status ?: '—');
    $statusLabels = [
        'available' => 'قابل فروش',
        'ready' => 'آماده',
        'pending' => 'در انتظار',
        'waiting' => 'در صف',
        'owned' => 'مالک‌شده',
        'sold' => 'فروخته‌شده',
    ];
    $statusLabel = $statusLabels[$status] ?? $status;
    $isOpenSupply = in_array($status, ['available', 'ready'], true);

    $img = $dog->primaryImageUrl() ?? null;
    if (! $img && $dog->image) {
        $raw = (string) $dog->image;
        $img = str_starts_with($raw, 'http')
            ? $raw
            : (str_starts_with($raw, 'images/') ? asset($raw) : asset('storage/'.ltrim($raw, '/')));
    }
    if (! $img) {
        $img = asset('images/demo/dogs/dog-01.jpg');
    }

    $gallery = collect($dog->media ?? [])
        ->map(function ($m) {
            $path = $m->path ?? $m->url ?? $m->image ?? null;
            if (! $path) {
                return null;
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return str_starts_with($path, 'images/') ? asset($path) : asset('storage/'.ltrim($path, '/'));
        })
        ->filter()
        ->unique()
        ->values();

    $frontendUrl = route('frontend.dog-supply.show', $dog->id);
@endphp

@include('admin.components.page-header', [
    'title' => $dog->title ?: 'جزئیات سگ',
    'subtitle' => '#'.$dog->id.' · '.($dog->breed->name ?? ($dog->breedName() ?? 'نژاد نامشخص')),
])

<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dogs.index') }}">بازگشت</a>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.dogs.edit', $dog) }}">ویرایش</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ $frontendUrl }}" target="_blank" rel="noopener">مشاهده در سایت</a>
    <form action="{{ route('admin.dogs.destroy', $dog) }}" method="POST" onsubmit="return confirm('این سگ حذف شود؟')" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">حذف</button>
    </form>
    <span class="sp-admin-status {{ $isOpenSupply ? 'is-open' : '' }}">{{ $statusLabel }}</span>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<section class="sp-admin-doghero card sp-admin-card mb-4">
    <div class="card-body">
        <div class="row gy-3 align-items-stretch">
            <div class="col-lg-5">
                <div class="sp-admin-doghero__media">
                    <img src="{{ $img }}" alt="{{ $dog->title }}" loading="lazy">
                </div>
                @if($gallery->isNotEmpty())
                    <div class="sp-admin-doghero__thumbs">
                        @foreach($gallery->take(6) as $thumb)
                            <img src="{{ $thumb }}" alt="" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    <div>
                        <h2 class="h4 mb-1">{{ $dog->title ?: 'بدون نام' }}</h2>
                        <p class="text-muted mb-0">
                            {{ $dog->breed->name ?? ($dog->breedName() ?? 'نژاد نامشخص') }}
                            · {{ $dog->city->name ?? 'شهر نامشخص' }}
                        </p>
                    </div>
                    <div class="text-left">
                        <div class="text-muted small">قیمت</div>
                        <div class="h4 mb-0 num">{{ $dog->price !== null ? number_format((int) $dog->price) : '—' }}</div>
                    </div>
                </div>

                <div class="sp-admin-dogfacts">
                    <div class="sp-admin-dogfact">
                        <span>سن</span>
                        <strong class="num">{{ $dog->age !== null ? $dog->age.' ماه' : '—' }}</strong>
                    </div>
                    <div class="sp-admin-dogfact">
                        <span>رنگ</span>
                        <strong>{{ $dog->color ?: '—' }}</strong>
                    </div>
                    <div class="sp-admin-dogfact">
                        <span>اندازه</span>
                        <strong>{{ $dog->size ?: '—' }}</strong>
                    </div>
                    <div class="sp-admin-dogfact">
                        <span>چشم</span>
                        <strong>{{ $dog->eye_color ?: '—' }}</strong>
                    </div>
                    <div class="sp-admin-dogfact">
                        <span>پوشش</span>
                        <strong>{{ $dog->cover_type ?: '—' }}</strong>
                    </div>
                    <div class="sp-admin-dogfact">
                        <span>شجره</span>
                        <strong>{{ $dog->shajare ? 'دارد' : 'ندارد' }}</strong>
                    </div>
                </div>

                <dl class="sp-admin-kv mt-3 mb-0">
                    <dt>مالک / ثبت‌کننده</dt>
                    <dd>
                        {{ $dog->owner->name ?? ('#'.($dog->user_id ?: '—')) }}
                        @if($dog->owner?->email)
                            <div class="text-muted small">{{ $dog->owner->email }}</div>
                        @endif
                    </dd>
                    <dt>شناسه</dt><dd class="num">{{ $dog->id }}</dd>
                    <dt>تاریخ ثبت</dt>
                    <dd class="num">{{ optional($dog->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') ?: '—' }}</dd>
                </dl>

                @if($dog->description)
                    <div class="mt-3">
                        <strong>توضیحات</strong>
                        <p class="mb-0 mt-1 text-muted" style="white-space:pre-wrap">{{ $dog->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="card sp-admin-card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs sp-admin-tabs" id="dogInfoTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">اطلاعات عمومی</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mother-tab" data-toggle="tab" href="#mother" role="tab">شجره مادر</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="father-tab" data-toggle="tab" href="#father" role="tab">شجره پدر</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                @include('admin.dogs.partials.general-info')
            </div>
            <div class="tab-pane fade" id="mother" role="tabpanel">
                @include('admin.dogs.partials.mother-info')
            </div>
            <div class="tab-pane fade" id="father" role="tabpanel">
                @include('admin.dogs.partials.father-info')
            </div>
        </div>
    </div>
</div>
@endsection