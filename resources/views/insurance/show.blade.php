@extends('layouts.app')

@section('title', ($insurance->name ?? 'بیمه').' — جزئیات')

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Tehran');
    $img = $insurance->logoUrl();
    $lede = \Illuminate\Support\Str::limit(strip_tags($insurance->description ?: 'پوشش بیمه حیوانات خانگی با مجوز مدت‌دار در داشبورد.'), 140);
    $fromPrice = $plans->isNotEmpty() ? (int) $plans->min('price') : null;
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
            <img class="sp-dog-show__photo" src="{{ $img }}" alt="{{ $insurance->name }}">
            <div>
                <p class="sp-provider__kicker">بیمه حیوانات</p>
                <h1 class="sp-provider__name">{{ $insurance->name }}</h1>
                <p class="sp-provider__lede">{{ $lede }}</p>
                <div class="sp-provider__chips">
                    <span class="sp-provider__chip is-accent">{{ $insurance->is_active ? 'فعال' : 'غیرفعال' }}</span>
                    @if($insurance->is_featured)
                        <span class="sp-provider__chip">ویژه</span>
                    @endif
                    @if($insurance->city?->name)
                        <span class="sp-provider__chip"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $insurance->city->name }}</span>
                    @endif
                    @if($fromPrice)
                        <span class="sp-provider__chip num">از {{ number_format($fromPrice) }} تومان</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="wrap sp-dog-show__layout">
        <div class="sp-dog-show__main">
            <section class="sp-provider__panel">
                <h2>درباره بیمه</h2>
                <p>{{ $insurance->description ?: 'توضیح بیشتری ثبت نشده است.' }}</p>
            </section>

            <section class="sp-provider__panel">
                <h2>اطلاعات تماس</h2>
                <div class="sp-provider__facts">
                    <div class="sp-provider__fact">
                        <span>تلفن</span>
                        <strong class="num">{{ $insurance->contact_phone ?: '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>ایمیل</span>
                        <strong>{{ $insurance->contact_email ?: '—' }}</strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>وب‌سایت</span>
                        <strong>
                            @if($insurance->website)
                                <a href="{{ $insurance->website }}" target="_blank" rel="noopener">{{ $insurance->website }}</a>
                            @else
                                —
                            @endif
                        </strong>
                    </div>
                    <div class="sp-provider__fact">
                        <span>شهر</span>
                        <strong>{{ $insurance->city?->name ?? '—' }}</strong>
                    </div>
                </div>
            </section>

            <section class="sp-provider__panel">
                <h2>طرح‌ها و مدت پوشش</h2>
                @if($plans->isEmpty())
                    <p class="sp-provider__empty">فعلاً طرح فعالی ثبت نشده است.</p>
                @else
                    <div class="sp-provider__list">
                        @foreach($plans as $plan)
                            <div class="sp-provider__list-item">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <strong>{{ $plan->name }} · <span class="num">{{ $plan->duration_months }}</span> ماه</strong>
                                    <p>
                                        {{ $plan->coverage_summary ?: 'پوشش بیمه‌ای' }}
                                        · <span class="num">{{ number_format((int) $plan->price) }}</span> تومان
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            @if($activeLicenses->isNotEmpty())
                <section class="sp-provider__panel">
                    <h2>مجوزهای فعال شما</h2>
                    <div class="sp-provider__list">
                        @foreach($activeLicenses as $lic)
                            <div class="sp-provider__list-item">
                                <i class="fas fa-id-card"></i>
                                <div>
                                    <strong class="num">{{ $lic->license_code }}</strong>
                                    <p>
                                        {{ $lic->plan?->name ?? 'طرح' }}
                                        · تا <span class="num">{{ optional($lic->ends_at)->timezone($tz)->format('Y/m/d') }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="sp-btn sp-btn--ghost" style="margin-top:12px" href="{{ route('account.insurance-licenses.index') }}">همه مجوزها</a>
                </section>
            @endif

            <section class="sp-provider__panel">
                <h2>چطور کار می‌کند؟</h2>
                <div class="sp-provider__list">
                    <div class="sp-provider__list-item"><i class="fas fa-1"></i><div><strong>طرح را انتخاب کنید</strong><p>مدت پوشش را از پنل خرید مشخص کنید.</p></div></div>
                    <div class="sp-provider__list-item"><i class="fas fa-2"></i><div><strong>پرداخت کنید</strong><p>هزینه از کیف پول سپاسیران کسر می‌شود.</p></div></div>
                    <div class="sp-provider__list-item"><i class="fas fa-3"></i><div><strong>مجوز در داشبورد</strong><p>مجوز با همان مدت در حساب شما فعال می‌شود.</p></div></div>
                </div>
            </section>
        </div>

        <aside class="sp-dog-show__aside">
            <div class="sp-provider__panel sp-provider__cta" id="insPlans">
                <h2 style="margin:0">خرید بیمه</h2>
                <p class="sp-provider__cta-note">پس از پرداخت، مجوز مدت‌دار در داشبورد شما صادر می‌شود.</p>

                @guest
                    <p class="sp-provider__cta-note">برای خرید وارد حساب شوید.</p>
                    <a class="sp-btn sp-btn--primary" href="{{ $loginUrl }}">ورود برای خرید</a>
                @else
                    @if($plans->isEmpty())
                        <p class="sp-provider__cta-note">طرح فعالی برای خرید وجود ندارد.</p>
                    @else
                        <form method="post" action="{{ route('frontend.clinic.insurance.purchase', $insurance->id) }}" style="display:grid;gap:12px;width:100%">
                            @csrf
                            <input type="hidden" name="payment_method" value="wallet">

                            <div class="field" style="margin:0">
                                <label for="plan_id">طرح / مدت</label>
                                <select name="plan_id" id="plan_id" required>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" @selected((string) old('plan_id') === (string) $plan->id || $loop->first)>
                                            {{ $plan->name }} — {{ $plan->duration_months }} ماه — {{ number_format((int) $plan->price) }} ت
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field" style="margin:0">
                                <label for="dog_id">سگ تحت پوشش (اختیاری)</label>
                                <select name="dog_id" id="dog_id">
                                    <option value="">— بدون انتخاب —</option>
                                    @foreach($dogs as $dog)
                                        <option value="{{ $dog->id }}" @selected((string) old('dog_id') === (string) $dog->id)>{{ $dog->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="sp-btn sp-btn--primary" style="width:100%">
                                <i class="fas fa-wallet"></i> پرداخت از کیف پول
                            </button>
                        </form>
                    @endif
                @endauth

                <a class="sp-btn sp-btn--ghost" style="width:100%;margin-top:8px" href="{{ route('frontend.clinic.insurance.index') }}">
                    بازگشت به فهرست بیمه‌ها
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
