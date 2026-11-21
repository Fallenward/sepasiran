@extends('layouts.app')

@section('title', trim(($trainer->name ?? '').' '.($trainer->family ?? '')).' — پرورش‌دهنده')

@section('content')
@php
    $fullName = trim(($trainer->name ?? '').' '.($trainer->family ?? ''));
    $about = trim((string) ($trainer->bio ?? ''));
    if ($about === '') {
        $about = $fullName.' پرورش‌دهنده سگ است. سگ‌های آگهی‌شده را ببینید یا برای مشاوره/بازدید مستقیم رزرو کنید.';
    }
    $dogs = $dogs ?? collect();
    $specialties = $specialties ?? collect();
@endphp

<x-provider-profile
    :provider="$trainer"
    role-label="پرورش‌دهنده"
    :lede="$cityName ? 'پرورش‌دهنده در '.$cityName.' — مشاهده سگ‌ها و رزرو مشاوره' : 'مشاهده سگ‌ها و رزرو مشاوره حضوری'"
    :about="$about"
    :book-url="$bookUrl"
    book-label="رزرو مشاوره با پرورش‌دهنده"
    book-hint="برای بازدید، مشاوره نژاد یا هماهنگی خرید، مستقیم درخواست رزرو بفرستید."
    :city-name="$cityName"
    :specialties="$specialties"
    :facts="$facts"
    hero-image="images/demo/dogs/dog-05.jpg"
>
    <section class="sp-provider__panel">
        <h2>سگ‌های این پرورش‌دهنده</h2>
        @if($dogs->isNotEmpty())
            <div class="sp-provider__grid">
                @foreach($dogs as $dog)
                    @php
                        $img = method_exists($dog, 'imageUrl') ? $dog->imageUrl() : null;
                        if (! $img) {
                            $img = !empty($dog->image)
                                ? (str_starts_with($dog->image, 'http') ? $dog->image : asset($dog->image))
                                : asset('images/demo/dogs/dog-02.jpg');
                        }
                        $dogUrl = route('frontend.dog-supply.show', $dog->id);
                    @endphp
                    <a class="sp-provider__card" href="{{ $dogUrl }}">
                        <img src="{{ $img }}" alt="{{ $dog->title }}">
                        <h3>{{ $dog->title ?? 'سگ' }}</h3>
                        <p>
                            @if(method_exists($dog, 'breedName') && $dog->breedName()) {{ $dog->breedName() }} · @endif
                            {{ $dog->status ?? '—' }}
                            @if($dog->age !== null) · {{ $dog->age }} ماه @endif
                        </p>
                    </a>
                @endforeach
            </div>
        @else
            <p class="sp-provider__empty">فعلاً آگهی سگی نیست؛ می‌توانید برای مشاوره رزرو کنید.</p>
        @endif
    </section>

    @if($specialties->isNotEmpty())
        <section class="sp-provider__panel">
            <h2>تخصص نژادی</h2>
            <div class="sp-provider__tags">
                @foreach($specialties as $spec)
                    <span class="sp-provider__tag">{{ $spec->name ?? $spec }}</span>
                @endforeach
            </div>
        </section>
    @endif

    <section class="sp-provider__panel">
        <h2>قبل از رزرو بدانید</h2>
        <div class="sp-provider__list">
            <div class="sp-provider__list-item"><i class="fas fa-search"></i><div><strong>مشاهده آگهی‌ها</strong><p>سگ‌های ثبت‌شده را در همین صفحه ببینید.</p></div></div>
            <div class="sp-provider__list-item"><i class="fas fa-comments"></i><div><strong>مشاوره</strong><p>برای پرسش درباره نژاد، شجره یا شرایط نگهداری رزرو کنید.</p></div></div>
            <div class="sp-provider__list-item"><i class="fas fa-home"></i><div><strong>بازدید</strong><p>پس از پذیرش درخواست، زمان بازدید هماهنگ می‌شود.</p></div></div>
        </div>
    </section>

    <x-slot:aside>
        <h2>دسترسی سریع</h2>
        <div class="sp-provider__list" style="margin-top:12px">
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.dog.supply.index') }}">همه آگهی‌های سگ</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.breeders.index') }}">سایر پرورش‌دهندگان</a>
        </div>
    </x-slot:aside>
</x-provider-profile>
@endsection