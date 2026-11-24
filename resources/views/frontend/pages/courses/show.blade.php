@extends('layouts.app')

@section('title', ($course->title ?? 'دوره').' — جزئیات')

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Tehran');
    $raw = $course->thumbnail ?? null;
    if ($raw) {
        $img = str_starts_with($raw, 'http')
            ? $raw
            : (str_starts_with($raw, 'images/')
                ? asset($raw)
                : asset('storage/'.ltrim($raw, '/')));
    } else {
        $img = asset('images/demo/hero/hero-03.jpg');
    }
    $lede = \Illuminate\Support\Str::limit(strip_tags($course->short_description ?: $course->description ?: 'دوره آموزشی سپاسیران.'), 160);
    $listPrice = (int) ($course->price ?? 0);
    $payPrice = (int) (($course->discount_price && $course->discount_price > 0 && $course->discount_price < $listPrice)
        ? $course->discount_price
        : $listPrice);
    $hasAccess = $enrollment && $enrollment->isActive();
@endphp

<div class="sp-dog-show">
    @if(session('success'))
        <div class="wrap" style="padding-top:16px">
            <div class="sp-provider__panel" style="border-color:rgba(126,200,160,.45)">{{ session('success') }}</div>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="wrap" style="padding-top:16px">
            <div class="sp-provider__panel" style="border-color:rgba(220,80,80,.45)">
                @foreach($errors->all() as $error)<p style="margin:0">{{ $error }}</p>@endforeach
            </div>
        </div>
    @endif

    <section class="sp-dog-show__hero" style="--sp-dog-hero:url('{{ $img }}')">
        <div class="sp-dog-show__hero-inner wrap">
            <img class="sp-dog-show__photo" src="{{ $img }}" alt="{{ $course->title }}">
            <div>
                <p class="sp-provider__kicker">دوره آموزشی</p>
                <h1 class="sp-provider__name">{{ $course->title }}</h1>
                <p class="sp-provider__lede">{{ $lede }}</p>
                <div class="sp-provider__chips">
                    @if($course->category?->name)
                        <span class="sp-provider__chip is-accent">{{ $course->category->name }}</span>
                    @endif
                    @if($course->level?->name)
                        <span class="sp-provider__chip">{{ $course->level->name }}</span>
                    @endif
                    @if($course->is_featured)
                        <span class="sp-provider__chip">ویژه</span>
                    @endif
                    @if($course->has_certificate)
                        <span class="sp-provider__chip">گواهی‌نامه</span>
                    @endif
                    <span class="sp-provider__chip num">{{ $lessonCount }} درس</span>
                    @if($durationMinutes > 0)
                        <span class="sp-provider__chip num">{{ $durationMinutes }} دقیقه</span>
                    @endif
                    <span class="sp-provider__chip num">
                        @if($payPrice <= 0)
                            رایگان
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
                <h2>درباره دوره</h2>
                <p>{!! nl2br(e($course->description ?: ($course->short_description ?: 'توضیح بیشتری ثبت نشده است.'))) !!}</p>
            </section>

            <section class="sp-provider__panel">
                <h2>اطلاعات دوره</h2>
                <div class="sp-provider__facts">
                    <div class="sp-provider__fact">
                        <span>مدرس</span>
                        <strong>{{ $course->users?->name ?? '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>دسته‌بندی</span>
                        <strong>{{ $course->category?->name ?? '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>سطح</span>
                        <strong>{{ $course->level?->name ?? '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>امتیاز</span>
                        <strong class="num">{{ number_format($averageRating, 1) }} / ۵</strong>
                    </div>
                </div>
            </section>

            <section class="sp-provider__panel">
                <h2>سرفصل‌ها و درس‌ها</h2>
                @if($parts->isEmpty() && $orphanLessons->isEmpty())
                    <p class="sp-provider__empty">هنوز درسی برای این دوره ثبت نشده است.</p>
                @else
                    <div class="sp-provider__list">
                        @foreach($parts as $part)
                            <div class="sp-provider__list-item" style="align-items:flex-start">
                                <i class="fas fa-folder-open"></i>
                                <div style="width:100%">
                                    <strong>{{ $part->title }}</strong>
                                    <p class="num" style="margin-bottom:8px">{{ $part->lessons->count() }} درس</p>
                                    @foreach($part->lessons as $lesson)
                                        <div style="display:flex;gap:10px;align-items:flex-start;padding:8px 0;border-top:1px solid rgba(0,0,0,.06)">
                                            <i class="fas {{ $lesson->is_preview || $hasAccess ? 'fa-play-circle' : 'fa-lock' }}" style="margin-top:3px"></i>
                                            <div>
                                                <strong>{{ $lesson->title }}</strong>
                                                <p style="margin:4px 0 0">
                                                    @if($lesson->duration_minutes)
                                                        <span class="num">{{ $lesson->duration_minutes }} دقیقه</span>
                                                    @endif
                                                    @if($lesson->is_preview)
                                                        · پیش‌نمایش رایگان
                                                    @elseif(! $hasAccess)
                                                        · پس از خرید
                                                    @endif
                                                    @if($lesson->description)
                                                        · {{ \Illuminate\Support\Str::limit(strip_tags($lesson->description), 90) }}
                                                    @endif
                                                </p>
                                                @if(($lesson->is_preview || $hasAccess) && $lesson->videoPublicUrl())
                                                    <a href="{{ $lesson->videoPublicUrl() }}" target="_blank" rel="noopener" class="sp-btn sp-btn--ghost" style="margin-top:8px;display:inline-flex">مشاهده ویدیو</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @foreach($orphanLessons as $lesson)
                            <div class="sp-provider__list-item">
                                <i class="fas {{ $lesson->is_preview || $hasAccess ? 'fa-play-circle' : 'fa-lock' }}"></i>
                                <div>
                                    <strong>{{ $lesson->title }}</strong>
                                    <p>
                                        @if($lesson->duration_minutes)
                                            <span class="num">{{ $lesson->duration_minutes }} دقیقه</span>
                                        @endif
                                        @if($lesson->is_preview)
                                            · پیش‌نمایش رایگان
                                        @elseif(! $hasAccess)
                                            · پس از خرید
                                        @endif
                                        @if($lesson->description)
                                            · {{ \Illuminate\Support\Str::limit(strip_tags($lesson->description), 100) }}
                                        @endif
                                    </p>
                                    @if(($lesson->is_preview || $hasAccess) && $lesson->videoPublicUrl())
                                        <a href="{{ $lesson->videoPublicUrl() }}" target="_blank" rel="noopener" class="sp-btn sp-btn--ghost" style="margin-top:8px;display:inline-flex">مشاهده ویدیو</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @if($hasAccess)
                <section class="sp-provider__panel">
                    <h2>دسترسی شما</h2>
                    <div class="sp-provider__list">
                        <div class="sp-provider__list-item">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong class="num">{{ $enrollment->enrollment_code }}</strong>
                                <p>
                                    فعال از
                                    <span class="num">{{ optional($enrollment->purchased_at)->timezone($tz)->format('Y/m/d H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <a class="sp-btn sp-btn--ghost" style="margin-top:12px" href="{{ route('account.course-enrollments.index') }}">دوره‌های خریداری‌شده</a>
                </section>
            @endif

            <section class="sp-provider__panel">
                <h2>چطور کار می‌کند؟</h2>
                <div class="sp-provider__list">
                    <div class="sp-provider__list-item"><i class="fas fa-1"></i><div><strong>سرفصل را ببینید</strong><p>درس‌ها و بخش‌های دوره را مرور کنید.</p></div></div>
                    <div class="sp-provider__list-item"><i class="fas fa-2"></i><div><strong>پرداخت کنید</strong><p>هزینه از کیف پول سپاسیران کسر می‌شود.</p></div></div>
                    <div class="sp-provider__list-item"><i class="fas fa-3"></i><div><strong>دسترسی فوری</strong><p>همه درس‌ها در حساب شما باز می‌شوند.</p></div></div>
                </div>
            </section>

            @if($comments->isNotEmpty())
                <section class="sp-provider__panel">
                    <h2>نظرها</h2>
                    <div class="sp-provider__list">
                        @foreach($comments->take(8) as $comment)
                            <div class="sp-provider__list-item">
                                <i class="fas fa-comment"></i>
                                <div>
                                    <strong>{{ $comment->name ?? 'کاربر' }}</strong>
                                    @if($comment->rate)
                                        · <span class="num">{{ $comment->rate }}/۵</span>
                                    @endif
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($comment->body ?? $comment->comment ?? ''), 140) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="sp-dog-show__aside">
            <div class="sp-provider__panel sp-provider__cta" id="courseBuy">
                <h2 style="margin:0">خرید دوره</h2>
                <p class="sp-provider__cta-note">پس از پرداخت، دسترسی کامل به همه درس‌ها در داشبورد شما فعال می‌شود.</p>

                <div class="sp-provider__facts" style="margin:12px 0">
                    <div class="sp-provider__fact">
                        <span>قیمت</span>
                        <strong class="num">
                            @if($payPrice <= 0)
                                رایگان
                            @else
                                @if($listPrice > $payPrice)
                                    <del style="opacity:.55;font-weight:400">{{ number_format($listPrice) }}</del>
                                    {{ number_format($payPrice) }} تومان
                                @else
                                    {{ number_format($payPrice) }} تومان
                                @endif
                            @endif
                        </strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>تعداد درس</span>
                        <strong class="num">{{ $lessonCount }}</strong>
                    </div>
                </div>

                @guest
                    <p class="sp-provider__cta-note">برای خرید وارد حساب شوید.</p>
                    <a class="sp-btn sp-btn--primary" href="{{ $loginUrl }}">ورود برای خرید</a>
                @else
                    @if($hasAccess)
                        <p class="sp-provider__cta-note" style="color:var(--sp-ok, #2a7a4b)">شما به این دوره دسترسی دارید.</p>
                        <a class="sp-btn sp-btn--primary" href="{{ route('account.course-enrollments.index') }}">مشاهده دوره‌های من</a>
                    @else
                        <form method="post" action="{{ route('frontend.courses.purchase', $course->slug) }}" style="display:grid;gap:12px;width:100%">
                            @csrf
                            <input type="hidden" name="payment_method" value="wallet">
                            <button type="submit" class="sp-btn sp-btn--primary" style="width:100%">
                                <i class="fas fa-wallet"></i>
                                @if($payPrice <= 0)
                                    ثبت‌نام رایگان
                                @else
                                    پرداخت از کیف پول
                                @endif
                            </button>
                        </form>
                    @endif
                @endauth

                <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" href="{{ route('frontend.online.courses') }}">
                    بازگشت به فهرست دوره‌ها
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
