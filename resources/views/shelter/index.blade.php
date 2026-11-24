@extends('layouts.app')

@section('title', 'پناهگاه')

@section('content')
@php
    $heroImage = asset('images/demo/hero/hero-01.jpg');
@endphp

<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ $heroImage }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>پناهگاه</span>
        </nav>
        <h1>پناهگاه سپاسیران</h1>
        <p>سگ‌های قابل پذیرش، گزارش بی‌سرپرست، داوطلبی و مسیر کلینیک/مربی — یک میز کار واقعی، نه صفحهٔ خالی.</p>
        <div class="sp-listing-hero__actions" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:16px">
            <a class="btn btn-primary" href="#shelter-dogs">سگ‌های آماده</a>
            <a class="btn btn-ghost" href="#shelter-help">ثبت گزارش / داوطلبی</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        @if(session('success'))
            <div class="sp-provider__panel" style="margin-bottom:20px;border-color:rgba(126,200,160,.45)">{{ session('success') }}</div>
        @endif

        <div class="sp-stat-row" aria-label="آمار پناهگاه" style="margin-bottom:28px">
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="dogs" /></span>
                <strong class="num">{{ number_format((int) $availableCount) }}</strong>
                <span>قابل پذیرش</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="trust" /></span>
                <strong class="num">{{ number_format((int) $adoptedCount) }}</strong>
                <span>واگذار / مالک شده</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="clinic" /></span>
                <strong class="num">{{ number_format((int) $clinicCount) }}</strong>
                <span>کلینیک</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="coach" /></span>
                <strong class="num">{{ number_format((int) $coachCount) }}</strong>
                <span>مربی همراه</span>
            </div>
        </div>

        <x-sp-section-head
            title="چه کاری می‌توانید انجام دهید؟"
            subtitle="هر مسیر به یک اقدام واقعی در سپاسیران وصل است."
            icon="services"
        />
        <div class="sp-service-lanes sp-service-lanes--compact" style="margin-top:12px;margin-bottom:28px">
            @foreach($actions as $action)
                <a class="sp-service-lane" href="{{ $action['href'] }}">
                    <span class="sp-service-lane__icon"><x-sp-icon :name="$action['icon']" /></span>
                    <h3>{{ $action['title'] }}</h3>
                    <p style="margin:0;font-size:.85rem;opacity:.75">{{ $action['hint'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="sp-dog-show__layout">
            <div class="sp-dog-show__main" style="display:grid;gap:20px">
                <section id="shelter-dogs" class="sp-provider__panel">
                    <x-sp-section-head
                        title="سگ‌های قابل پذیرش"
                        subtitle="آگهی‌های available / ready از دیتابیس — برای جزئیات و سفارش باز کنید."
                        icon="dogs"
                    />
                    <div class="grid grid-3" style="margin-top:16px">
                        @forelse($dogs as $dog)
                            <div class="sp-listing-card">
                                @include('components.dog-card', ['item' => $dog])
                            </div>
                        @empty
                            <div class="panel" style="grid-column:1/-1;text-align:center">
                                <p style="margin:0;color:var(--sp-text-muted)">فعلاً سگ آماده‌ای نیست. گزارش بی‌سرپرست بفرستید یا بعداً سر بزنید.</p>
                            </div>
                        @endforelse
                    </div>
                    <div style="margin-top:16px">
                        <a class="btn btn-primary" href="{{ route('frontend.dog.supply.index') }}">همه آگهی‌های سگ</a>
                    </div>
                </section>

                <section class="sp-provider__panel">
                    <x-sp-section-head
                        title="مسیر پذیرش"
                        subtitle="چهار قدم شفاف از انتخاب تا مراقبت."
                        icon="steps"
                    />
                    <ol class="sp-steps" style="margin-top:12px">
                        <li class="sp-step">
                            <span class="sp-step__icon"><x-sp-icon name="dogs" /></span>
                            <div>
                                <h3>سگ را انتخاب کنید</h3>
                                <p>از لیست بالا یا صفحه سگ‌ها آگهی را باز کنید.</p>
                            </div>
                        </li>
                        <li class="sp-step">
                            <span class="sp-step__icon"><x-sp-icon name="cta" /></span>
                            <div>
                                <h3>درخواست بدهید</h3>
                                <p>سفارش بزنید یا فرم «درخواست پذیرش» را پر کنید.</p>
                            </div>
                        </li>
                        <li class="sp-step">
                            <span class="sp-step__icon"><x-sp-icon name="clinic" /></span>
                            <div>
                                <h3>معاینه و واکسن</h3>
                                <p>از کلینیک‌های سپاسیران برای چکاپ اولیه استفاده کنید.</p>
                            </div>
                        </li>
                        <li class="sp-step">
                            <span class="sp-step__icon"><x-sp-icon name="coach" /></span>
                            <div>
                                <h3>عادت به خانه</h3>
                                <p>با مربی، رفتار و آموزش اولیه را شروع کنید.</p>
                            </div>
                        </li>
                    </ol>
                </section>
            </div>

            <aside class="sp-dog-show__aside" style="display:grid;gap:16px;align-content:start">
                <div id="shelter-help" class="sp-provider__panel sp-provider__cta">
                    <h2 style="margin:0">کمک به پناهگاه</h2>
                    <p class="sp-provider__cta-note">گزارش بی‌سرپرست، داوطلبی، حمایت مالی یا درخواست پذیرش — مستقیم ثبت می‌شود.</p>
                    <form method="POST" action="{{ route('frontend.shelter.store') }}" style="display:grid;gap:12px;margin-top:12px">
                        @csrf
                        <div class="field" style="margin:0">
                            <label for="intent">نوع درخواست</label>
                            <select id="intent" name="intent" required>
                                <option value="report" @selected(old('intent') === 'report')>گزارش سگ بی‌سرپرست</option>
                                <option value="volunteer" @selected(old('intent') === 'volunteer')>داوطلبی حضوری</option>
                                <option value="donate" @selected(old('intent') === 'donate')>حمایت مالی</option>
                                <option value="adopt" @selected(old('intent', 'adopt') === 'adopt')>درخواست پذیرش</option>
                            </select>
                            @error('intent')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="name">نام</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="email">ایمیل</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="phone">تلفن</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required class="num">
                            @error('phone')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <div class="field" style="margin:0">
                            <label for="city">شهر (اختیاری)</label>
                            <input id="city" type="text" name="city" value="{{ old('city') }}">
                        </div>
                        <div class="field" style="margin:0">
                            <label for="message">توضیح</label>
                            <textarea id="message" name="message" rows="4" required placeholder="موقعیت، نژاد تقریبی، وضعیت سلامت، یا نوع کمکی که می‌توانید بکنید…">{{ old('message') }}</textarea>
                            @error('message')<p class="sp-support__hint">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="sp-btn sp-btn--primary">ارسال درخواست</button>
                    </form>
                    <button type="button" class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" data-sp-support-open data-sp-support-view="new" data-sp-support-subject="پناهگاه" data-sp-support-body="سلام، درباره پناهگاه سؤال دارم:">چت پشتیبانی</button>
                </div>

                <div class="sp-provider__panel">
                    <h2 style="margin:0 0 8px">سوالات متداول</h2>
                    <div class="sp-provider__list">
                        @foreach([
                            ['q' => 'پذیرش رایگان است؟', 'a' => 'بسته به آگهی متفاوت است؛ جزئیات قیمت و وضعیت در صفحه هر سگ آمده است.'],
                            ['q' => 'سگ بی‌سرپرست پیدا کردم؟', 'a' => 'فرم گزارش را بفرستید یا تیکت پشتیبانی باز کنید — موقعیت و عکس کمک بزرگی است.'],
                            ['q' => 'چطور داوطلب شوم؟', 'a' => 'نوع «داوطلبی حضوری» را انتخاب کنید و زمان/شهر را بنویسید.'],
                        ] as $faq)
                            <div class="sp-provider__list-item">
                                <i class="fas fa-question-circle"></i>
                                <div>
                                    <strong>{{ $faq['q'] }}</strong>
                                    <p>{{ $faq['a'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection