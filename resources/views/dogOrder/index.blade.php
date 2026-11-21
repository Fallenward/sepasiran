@extends('layouts.app')

@section('title', ($dog->title ?? 'سگ').' — جزئیات')

@section('content')
@php
    $img = method_exists($dog, 'primaryImageUrl') ? $dog->primaryImageUrl() : null;
    if (! $img) {
        $img = !empty($dog->image)
            ? (str_starts_with($dog->image, 'http') || str_starts_with($dog->image, '/') ? $dog->image : asset($dog->image))
            : asset('images/demo/dogs/dog-02.jpg');
    }
    $breedName = method_exists($dog, 'breedName') ? $dog->breedName() : (optional($dog->breed)->name ?? '—');
    $ownerName = $ownerProfile['full_name'] ?? trim(($owner->name ?? '').' '.($owner->family ?? ''));
    $ownerName = $ownerName !== '' ? $ownerName : 'مالک نامشخص';
    $statusLabels = [
        'available' => 'برای فروش',
        'ready' => 'آماده فروش',
        'for_sale' => 'برای فروش',
        'for-sale' => 'برای فروش',
        'owned' => 'مالکیت شخصی',
        'sold' => 'فروخته‌شده',
        'pending' => 'در انتظار',
        'waiting' => 'در انتظار پرداخت',
    ];
    $statusLabel = $statusLabels[$dog->status] ?? $dog->status;
@endphp

<div class="sp-dog-show">
    @if(session('success'))
        <div class="wrap" style="padding-top:16px">
            <div class="sp-provider__panel" style="border-color:rgba(126,200,160,.45)">{{ session('success') }}</div>
        </div>
    @endif
    @if($errors->any())
        <div class="wrap" style="padding-top:16px">
            <div class="sp-provider__panel" style="border-color:rgba(220,80,80,.45)">
                @foreach($errors->all() as $error)<p style="margin:0">{{ $error }}</p>@endforeach
            </div>
        </div>
    @endif

    <section class="sp-dog-show__hero" style="--sp-dog-hero:url('{{ $img }}')">
        <div class="sp-dog-show__hero-inner wrap">
            <img class="sp-dog-show__photo" src="{{ $img }}" alt="{{ $dog->title }}">
            <div>
                <p class="sp-provider__kicker">آگهی سگ</p>
                <h1 class="sp-provider__name">{{ $dog->title }}</h1>
                <p class="sp-provider__lede">{{ $breedName }}@if($dog->city) · {{ $dog->city->name }}@endif</p>
                <div class="sp-provider__chips">
                    <span class="sp-provider__chip is-accent">{{ $statusLabel }}</span>
                    @if($dog->price)
                        <span class="sp-provider__chip num">{{ number_format((int)$dog->price) }} تومان</span>
                    @endif
                    @if($dog->age)
                        <span class="sp-provider__chip">{{ $dog->age }} ماه</span>
                    @endif
                    @if($dog->color)
                        <span class="sp-provider__chip">{{ $dog->color }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="wrap sp-dog-show__layout">
        <div class="sp-dog-show__main">
            <section class="sp-provider__panel">
                <h2>مشخصات سگ</h2>
                <div class="sp-provider__facts">
                    <div class="sp-provider__fact"><span>نژاد</span><strong>{{ $breedName }}</strong></div>
                    <div class="sp-provider__fact"><span>سن</span><strong class="num">{{ $dog->age ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>رنگ</span><strong>{{ $dog->color ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>رنگ چشم</span><strong>{{ $dog->eye_color ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>پوشش</span><strong>{{ $dog->cover_type ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>اندازه</span><strong>{{ $dog->size ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>شهر</span><strong>{{ $dog->city->name ?? '—' }}</strong></div>
                    <div class="sp-provider__fact"><span>شجره</span><strong>{{ $dog->shajare ? 'دارد' : 'ندارد' }}</strong></div>
                </div>
            </section>

            <section class="sp-provider__panel">
                <h2>درباره سگ</h2>
                <p>{{ $dog->description ?: 'توضیح بیشتری ثبت نشده است.' }}</p>
            </section>

            @if($dog->shajare)
                <section class="sp-provider__panel">
                    <h2>شجره‌نامه</h2>
                    <div class="sp-provider__list">
                        <div class="sp-provider__list-item"><i class="fas fa-male"></i><div><strong>پدر</strong><p>{{ $dog->father_name ?: '—' }} @if($dog->fatherBreed) · {{ $dog->fatherBreed->name }}@endif</p></div></div>
                        <div class="sp-provider__list-item"><i class="fas fa-female"></i><div><strong>مادر</strong><p>{{ $dog->mother_name ?: '—' }} @if($dog->motherBreed) · {{ $dog->motherBreed->name }}@endif</p></div></div>
                    </div>
                </section>
            @endif

            <section class="sp-provider__panel sp-provider__info-card">
                <h2>اطلاعات مالک</h2>
                @if($owner)
                    <div class="sp-provider__info-main">
                        <img class="sp-provider__info-avatar" src="{{ $owner->avatarUrl() ?: asset('images/demo/dogs/dog-02.jpg') }}" alt="{{ $ownerName }}">
                        <div>
                            <strong class="sp-provider__info-name">{{ $ownerName }}</strong>
                            <p class="sp-provider__info-role">
                                @if($ownerProfile && $ownerProfile['city_name']){{ $ownerProfile['city_name'] }} · @endif
                                @if($ownerProfile && $ownerProfile['is_verified'])احراز هویت شده@else عضو سپاس‌ایران@endif
                            </p>
                            @if($ownerProfile)
                                <x-provider-rating :avg="$ownerProfile['rating_avg']" :count="$ownerProfile['rating_count']" />
                            @endif
                        </div>
                    </div>
                    @if($ownerProfile && $ownerProfile['specialties']->isNotEmpty())
                        <div class="sp-provider__tags" style="margin-top:12px">
                            @foreach($ownerProfile['specialties']->take(6) as $spec)
                                <span class="sp-provider__tag">{{ $spec }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if($owner->bio)
                        <p class="sp-provider__cta-note" style="margin-top:12px">{{ \Illuminate\Support\Str::limit(strip_tags($owner->bio), 180) }}</p>
                    @endif
                    @if($owner->hasRole('breeder'))
                        <a class="sp-btn sp-btn--ghost" style="margin-top:12px" href="{{ route('frontend.breeders.show', $owner->id) }}">پروفایل پرورش‌دهنده</a>
                    @elseif($owner->hasRole('coach'))
                        <a class="sp-btn sp-btn--ghost" style="margin-top:12px" href="{{ route('frontend.coaches.show', $owner->id) }}">پروفایل مربی</a>
                    @endif
                @else
                    <p class="sp-provider__empty">مالک ثبت نشده است.</p>
                @endif
            </section>
        </div>

        <aside class="sp-dog-show__aside">
            <div class="sp-provider__panel sp-provider__cta">
                <h2 style="margin:0">خرید سگ</h2>
                @if($dog->price)
                    <p class="sp-provider__cta-note num" style="font-size:1.2rem;font-weight:700;color:var(--sp-gold-soft)">{{ number_format((int)$dog->price) }} تومان</p>
                @endif

                @auth
                    @if($isOwner)
                        <p class="sp-provider__cta-note">این سگ متعلق به شماست.</p>
                    @elseif($pendingOrder)
                        <p class="sp-provider__cta-note">سفارش شما ثبت شده. با پرداخت از کیف پول، مالکیت منتقل می‌شود.</p>
                        <form method="post" action="{{ route('frontend.dog.order.pay', [$dog->id, $pendingOrder->id]) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="wallet">
                            <button type="submit" class="sp-btn sp-btn--primary" style="width:100%">
                                <i class="fas fa-wallet"></i> پرداخت و انتقال مالکیت
                            </button>
                        </form>
                    @elseif($dog->isForSale())
                        <p class="sp-provider__cta-note">این سگ برای فروش است. ابتدا سفارش ثبت کنید، سپس پرداخت را کامل کنید.</p>
                        <form method="post" action="{{ route('frontend.dog.order.store', $dog->id) }}">
                            @csrf
                            <button type="submit" class="sp-btn sp-btn--primary" style="width:100%">
                                <i class="far fa-shopping-bag"></i> ثبت سفارش خرید
                            </button>
                        </form>
                    @else
                        <p class="sp-provider__cta-note">این سگ در حال حاضر برای فروش نیست.</p>
                    @endif
                @else
                    @if($dog->isForSale())
                        <p class="sp-provider__cta-note">برای ثبت سفارش و خرید وارد شوید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('login') }}">ورود برای خرید</a>
                    @else
                        <p class="sp-provider__cta-note">این سگ در حال حاضر برای فروش نیست.</p>
                    @endif
                @endauth
            </div>
        </aside>
    </div>
</div>
@endsection