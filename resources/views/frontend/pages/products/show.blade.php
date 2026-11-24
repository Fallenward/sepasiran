@extends('layouts.app')

@section('title', ($product->title ?? 'محصول').' — فروشگاه')

@section('content')
@php
    $raw = $product->thumbnail ?? null;
    if ($raw) {
        $img = str_starts_with($raw, 'http')
            ? $raw
            : (str_starts_with($raw, 'images/')
                ? asset($raw)
                : asset('storage/products/'.ltrim($raw, '/')));
    } else {
        $img = asset('images/demo/products/product-01.jpg');
    }

    $listPrice = (int) ($product->price ?? 0);
    $payPrice = (int) (($product->discount_price && $product->discount_price > 0 && $product->discount_price < $listPrice)
        ? $product->discount_price
        : $listPrice);
    $inStock = (int) ($product->stock ?? 0) > 0 && ($product->is_active ?? true);
    $lede = \Illuminate\Support\Str::limit(strip_tags($product->description ?: 'محصول فروشگاه سپاسیران.'), 140);
@endphp

<div class="sp-dog-show">
    <section class="sp-dog-show__hero" style="--sp-dog-hero:url('{{ $img }}')">
        <div class="sp-dog-show__hero-inner wrap">
            <img class="sp-dog-show__photo" src="{{ $img }}" alt="{{ $product->title }}">
            <div>
                <p class="sp-provider__kicker">فروشگاه</p>
                <h1 class="sp-provider__name">{{ $product->title }}</h1>
                <p class="sp-provider__lede">{{ $lede }}</p>
                <div class="sp-provider__chips">
                    @if($product->category?->name)
                        <span class="sp-provider__chip is-accent">{{ $product->category->name }}</span>
                    @endif
                    @if($product->is_featured)
                        <span class="sp-provider__chip">ویژه</span>
                    @endif
                    <span class="sp-provider__chip {{ $inStock ? '' : 'is-danger' }}">
                        {{ $inStock ? 'موجود' : 'ناموجود' }}
                    </span>
                    <span class="sp-provider__chip num">
                        @if($payPrice <= 0)
                            رایگان
                        @elseif($listPrice > $payPrice)
                            {{ number_format($payPrice) }} تومان
                        @else
                            {{ number_format($payPrice) }} تومان
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="wrap sp-dog-show__layout">
        <div class="sp-dog-show__main">
            <section class="sp-provider__panel">
                <h2>درباره محصول</h2>
                <p>{!! nl2br(e($product->description ?: 'توضیح بیشتری ثبت نشده است.')) !!}</p>
            </section>

            <section class="sp-provider__panel">
                <h2>اطلاعات محصول</h2>
                <div class="sp-provider__facts">
                    <div class="sp-provider__fact">
                        <span>قیمت</span>
                        <strong class="num">
                            @if($listPrice > $payPrice && $payPrice > 0)
                                <del style="opacity:.55;font-weight:400">{{ number_format($listPrice) }}</del>
                                {{ number_format($payPrice) }} تومان
                            @elseif($payPrice > 0)
                                {{ number_format($payPrice) }} تومان
                            @else
                                —
                            @endif
                        </strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>موجودی</span>
                        <strong class="num">{{ $inStock ? number_format((int) $product->stock) : '۰' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>دسته‌بندی</span>
                        <strong>{{ $product->category?->name ?? '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>کد کالا</span>
                        <strong class="num">{{ $product->sku ?: '—' }}</strong>
                    </div>
                </div>
            </section>

            @if(isset($recentProducts) && $recentProducts->isNotEmpty())
                <section class="sp-provider__panel">
                    <h2>محصولات مرتبط</h2>
                    <div class="grid grid-4" style="margin-top:12px">
                        @foreach($recentProducts as $related)
                            <x-product-card :item="$related" :showRoute="'frontend.products.show'" />
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="sp-dog-show__aside">
            <div class="sp-provider__panel sp-provider__cta">
                <h2 style="margin:0">خرید محصول</h2>
                <p class="sp-provider__cta-note">قیمت و موجودی به‌روز است؛ از همین‌جا به فروشگاه برگردید یا لینک خرید را باز کنید.</p>

                <div class="sp-provider__facts" style="margin:12px 0">
                    <div class="sp-provider__fact">
                        <span>مبلغ</span>
                        <strong class="num">
                            @if($payPrice > 0)
                                {{ number_format($payPrice) }} تومان
                            @else
                                —
                            @endif
                        </strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>وضعیت</span>
                        <strong>{{ $inStock ? 'آماده ارسال' : 'ناموجود' }}</strong>
                    </div>
                </div>

                @if($product->buy_link)
                    <a class="sp-btn sp-btn--primary" style="width:100%" href="{{ $product->buy_link }}" target="_blank" rel="noopener">
                        <i class="fas fa-external-link-alt"></i> خرید آنلاین
                    </a>
                @elseif($inStock)
                    <a class="sp-btn sp-btn--primary" style="width:100%" href="{{ route('frontend.stores.index') }}">
                        <i class="fas fa-store"></i> ادامه در فروشگاه
                    </a>
                @else
                    <p class="sp-provider__cta-note">این محصول فعلاً موجود نیست.</p>
                @endif

                <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" href="{{ route('frontend.stores.index') }}">
                    بازگشت به فهرست محصولات
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection