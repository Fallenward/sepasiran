@extends('layouts.app')

@section('title', $title ?? 'پشتیبانی')

@section('content')
<section class="sp-listing-hero">
    <div class="sp-listing-hero__bg" aria-hidden="true">
        <img src="{{ asset('images/demo/hero/hero-03.jpg') }}" alt="">
    </div>
    <div class="wrap sp-listing-hero__inner">
        <nav class="sp-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('frontend.home') }}">خانه</a>
            <span class="sp-breadcrumb__sep">/</span>
            <span>پشتیبانی</span>
        </nav>
        <h1>میز پشتیبانی</h1>
        <p>موضوع را انتخاب کنید، تیکت‌های باز را ببینید، یا مستقیم با تیم چت کنید.</p>
        <div class="sp-listing-hero__actions" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:16px">
            @if($isAuth ?? false)
                <button type="button" class="btn btn-primary" data-sp-support-open data-sp-support-view="new">تیکت جدید</button>
                <button type="button" class="btn btn-ghost" data-sp-support-open>باز کردن چت</button>
            @else
                <a class="btn btn-primary" href="{{ $loginUrl }}">ورود برای ارسال تیکت</a>
                <a class="btn btn-ghost" href="{{ route('frontend.contact.index') }}">فرم تماس عمومی</a>
            @endif
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="sp-stat-row" aria-label="وضعیت پشتیبانی" style="margin-bottom:28px">
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="cta" /></span>
                <strong class="num">{{ $isAuth ? number_format((int) $openCount) : '—' }}</strong>
                <span>تیکت باز</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="spark" /></span>
                <strong class="num">{{ $isAuth ? number_format((int) $unread) : '—' }}</strong>
                <span>پیام نخوانده</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="steps" /></span>
                <strong class="num">۲۴س</strong>
                <span>هدف پاسخ</span>
            </div>
            <div class="sp-stat">
                <span class="sp-stat__icon"><x-sp-icon name="trust" /></span>
                <strong>چت زنده</strong>
                <span>ویجت همه صفحات</span>
            </div>
        </div>

        <div class="sp-dog-show__layout">
            <div class="sp-dog-show__main" style="display:grid;gap:20px">
                <section class="sp-provider__panel">
                    <x-sp-section-head
                        title="موضوع سریع"
                        subtitle="با یک کلیک تیکت با موضوع آماده باز می‌شود."
                        icon="services"
                    />
                    <div class="sp-service-lanes sp-service-lanes--compact" style="margin-top:12px">
                        @foreach($topics as $topic)
                            @if($isAuth ?? false)
                                <button
                                    type="button"
                                    class="sp-service-lane"
                                    data-sp-support-open
                                    data-sp-support-view="new"
                                    data-sp-support-subject="{{ $topic['subject'] }}"
                                    data-sp-support-body="{{ $topic['body'] }}"
                                >
                                    <span class="sp-service-lane__icon"><x-sp-icon :name="$topic['icon']" /></span>
                                    <h3>{{ $topic['title'] }}</h3>
                                    <p style="margin:0;font-size:.85rem;opacity:.75">{{ $topic['hint'] }}</p>
                                </button>
                            @else
                                <a class="sp-service-lane" href="{{ $loginUrl }}">
                                    <span class="sp-service-lane__icon"><x-sp-icon :name="$topic['icon']" /></span>
                                    <h3>{{ $topic['title'] }}</h3>
                                    <p style="margin:0;font-size:.85rem;opacity:.75">{{ $topic['hint'] }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </section>

                <section class="sp-provider__panel">
                    <x-sp-section-head
                        title="سوالات پرتکرار"
                        subtitle="قبل از تیکت این‌ها را ببینید — شاید همان جواب باشد."
                        icon="steps"
                    />
                    <div class="sp-provider__list" style="margin-top:12px">
                        @foreach([
                            ['q' => 'چطور تیکت بسازم؟', 'a' => 'وارد شوید، موضوع سریع را بزنید یا از آیکون چت پایین صفحه «تیکت جدید» را باز کنید. پیوست هم می‌توانید بفرستید.'],
                            ['q' => 'پاسخ را کجا می‌بینم؟', 'a' => 'همین صفحه در میز تیکت‌ها، و همچنین ویجت چت شناور روی همه صفحات — با نشان نخوانده.'],
                            ['q' => 'برای پیام عمومی چطور؟', 'a' => 'اگر ورود ندارید یا موضوع عمومی است، از صفحه تماس استفاده کنید؛ برای پیگیری زنده حتماً تیکت بزنید.'],
                            ['q' => 'چقدر طول می‌کشد؟', 'a' => 'هدف ما پاسخ در کمتر از یک روز کاری است؛ تیکت‌های باز در پنل ادمین فوراً هشدار می‌گیرند.'],
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
                </section>
            </div>

            <aside class="sp-dog-show__aside" style="display:grid;gap:16px;align-content:start">
                <div class="sp-provider__panel sp-provider__cta">
                    <h2 style="margin:0">میز تیکت‌ها</h2>
                    @if($isAuth ?? false)
                        <p class="sp-provider__cta-note">آخرین تیکت‌های شما — برای ادامه گفتگو روی هر کدام بزنید.</p>
                        @forelse($tickets as $ticket)
                            <button
                                type="button"
                                class="sp-support-desk__row"
                                data-sp-support-open
                                data-sp-support-ticket="{{ $ticket->id }}"
                                style="width:100%;text-align:right;display:flex;justify-content:space-between;gap:10px;align-items:flex-start;padding:12px 0;border:0;border-bottom:1px solid rgba(0,0,0,.08);background:transparent;cursor:pointer"
                            >
                                <span>
                                    <strong style="display:block">{{ $ticket->subject }}</strong>
                                    <small class="num">{{ $ticket->code }} · {{ optional($ticket->last_message_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</small>
                                </span>
                                <span style="display:grid;gap:4px;justify-items:end">
                                    <em style="font-style:normal;font-size:.78rem;padding:2px 8px;border-radius:999px;background:rgba(0,0,0,.06)">{{ $statusLabels[$ticket->status] ?? $ticket->status }}</em>
                                    @if($ticket->unread_for_user)
                                        <em class="num" style="font-style:normal;color:#b45309">{{ $ticket->unread_for_user }} جدید</em>
                                    @endif
                                </span>
                            </button>
                        @empty
                            <p class="sp-provider__cta-note">هنوز تیکتی ندارید. از موضوع سریع شروع کنید.</p>
                            <button type="button" class="sp-btn sp-btn--primary" style="width:100%" data-sp-support-open data-sp-support-view="new">اولین تیکت</button>
                        @endforelse
                        @if($tickets->isNotEmpty())
                            <button type="button" class="sp-btn sp-btn--ghost" style="width:100%;margin-top:12px" data-sp-support-open>همه در چت</button>
                        @endif
                    @else
                        <p class="sp-provider__cta-note">با ورود، تیکت‌های باز، پیام نخوانده و چت زنده اینجا می‌آید.</p>
                        <a class="sp-btn sp-btn--primary" style="width:100%" href="{{ $loginUrl }}">ورود برای ارسال تیکت</a>
                        <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" href="{{ route('frontend.contact.index') }}">تماس عمومی</a>
                    @endif
                </div>

                <div class="sp-provider__panel">
                    <h2 style="margin:0 0 8px">راه‌های دیگر</h2>
                    <div class="sp-provider__facts">
                        <div class="sp-provider__fact">
                            <span>چت شناور</span>
                            <strong>همیشه پایین صفحه</strong>
                        </div>
                        <div class="sp-provider__fact">
                            <span>تماس</span>
                            <strong><a href="{{ route('frontend.contact.index') }}">فرم پیام</a></strong>
                        </div>
                        <div class="sp-provider__fact">
                            <span>درباره ما</span>
                            <strong><a href="{{ route('frontend.about') }}">داستان سپاسیران</a></strong>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection