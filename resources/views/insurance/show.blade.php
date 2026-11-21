@extends('frontend.layouts.app')

@section('title', ($insurance->name ?? 'بیمه').' — سپاسیران')

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Tehran');
@endphp
<div class="sp-ins">
    <section class="sp-ins__hero">
        <div class="sp-ins__hero-bg" style="background-image:url('{{ $insurance->logoUrl() }}')" aria-hidden="true"></div>
        <div class="sp-ins__hero-scrim" aria-hidden="true"></div>
        <div class="wrap sp-ins__hero-inner">
            <p class="sp-ins__brand">سپاسیران · بیمه</p>
            <h1>{{ $insurance->name }}</h1>
            <p class="sp-ins__lede">{{ \Illuminate\Support\Str::limit(strip_tags($insurance->description ?: 'پوشش درمانی برای حیوان خانگی شما — طرح را انتخاب و مجوز مدت‌دار دریافت کنید.'), 140) }}</p>
            <div class="sp-ins__hero-actions">
                <a class="btn btn-primary" href="#insPlans">انتخاب طرح و خرید</a>
                <a class="sp-ins__ghost" href="{{ route('frontend.clinic.insurance.index') }}">بازگشت به فهرست</a>
            </div>
        </div>
    </section>

    <div class="wrap sp-ins__body">
        @if($errors->any())
            <div class="sp-ins__alert" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        @if($activeLicenses->isNotEmpty())
            <section class="sp-ins__section">
                <h2>مجوزهای فعال شما برای این بیمه</h2>
                <p class="sp-ins__hint">این مجوزها تا پایان اعتبار در داشبورد هم نمایش داده می‌شوند.</p>
                <div class="sp-ins__license-row">
                    @foreach($activeLicenses as $lic)
                        <article class="sp-ins__license">
                            <span class="sp-ins__license-code num">{{ $lic->license_code }}</span>
                            <strong>{{ $lic->plan?->name ?? 'طرح' }}</strong>
                            <small>اعتبار تا <span class="num">{{ optional($lic->ends_at)->timezone($tz)->format('Y/m/d') }}</span></small>
                        </article>
                    @endforeach
                </div>
                <a class="sp-ins__link" href="{{ route('account.insurance-licenses.index') }}">مشاهده همه مجوزها</a>
            </section>
        @endif

        <section class="sp-ins__section" id="insPlans">
            <h2>طرح‌ها و مدت پوشش</h2>
            <p class="sp-ins__hint">پس از پرداخت، یک مجوز با همان مدت انتخابی در داشبورد شما صادر می‌شود.</p>

            @guest
                <div class="sp-ins__gate">
                    <p>برای خرید بیمه وارد حساب شوید.</p>
                    <a class="btn btn-primary" href="{{ $loginUrl }}">ورود و ادامه</a>
                </div>
            @else
                @if($plans->isEmpty())
                    <p class="sp-ins__hint">فعلاً طرح فعالی برای این بیمه ثبت نشده است.</p>
                @else
                    <form method="post" action="{{ route('frontend.clinic.insurance.purchase', $insurance->id) }}" class="sp-ins__buy" id="insBuyForm">
                        @csrf
                        <input type="hidden" name="payment_method" value="wallet">

                        <div class="sp-ins__plans" role="radiogroup" aria-label="طرح بیمه">
                            @foreach($plans as $plan)
                                <label class="sp-ins__plan {{ (string) old('plan_id') === (string) $plan->id ? 'is-active' : '' }}">
                                    <input type="radio" name="plan_id" value="{{ $plan->id }}"
                                           @checked((string) old('plan_id') === (string) $plan->id || ($loop->first && ! old('plan_id')))
                                           required>
                                    <span class="sp-ins__plan-name">{{ $plan->name }}</span>
                                    <span class="sp-ins__plan-meta"><span class="num">{{ $plan->duration_months }}</span> ماه پوشش</span>
                                    @if($plan->coverage_summary)
                                        <span class="sp-ins__plan-cover">{{ $plan->coverage_summary }}</span>
                                    @endif
                                    <span class="sp-ins__plan-price num">{{ number_format((int) $plan->price) }} تومان</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="sp-ins__dog">
                            <label for="dog_id">سگ تحت پوشش (اختیاری)</label>
                            <select name="dog_id" id="dog_id">
                                <option value="">— بدون انتخاب —</option>
                                @foreach($dogs as $dog)
                                    <option value="{{ $dog->id }}" @selected((string) old('dog_id') === (string) $dog->id)>{{ $dog->title }}</option>
                                @endforeach
                            </select>
                            @if($dogs->isEmpty())
                                <p class="sp-ins__hint">سگی ثبت نکرده‌اید. می‌توانید بعداً از <a href="{{ route('account.dogs.create') }}">سگ‌های من</a> اضافه کنید.</p>
                            @endif
                        </div>

                        <p class="sp-ins__hint">پرداخت از کیف پول سپاسیران انجام می‌شود.</p>
                        <button type="submit" class="btn btn-primary">پرداخت و صدور مجوز</button>
                    </form>
                @endif
            @endguest
        </section>

        <section class="sp-ins__section sp-ins__facts">
            <h2>اطلاعات تماس</h2>
            <ul>
                @if($insurance->contact_phone)
                    <li><span>تلفن</span><strong class="num">{{ $insurance->contact_phone }}</strong></li>
                @endif
                @if($insurance->contact_email)
                    <li><span>ایمیل</span><strong>{{ $insurance->contact_email }}</strong></li>
                @endif
                @if($insurance->website)
                    <li><span>وب‌سایت</span><a href="{{ $insurance->website }}" target="_blank" rel="noopener">{{ $insurance->website }}</a></li>
                @endif
            </ul>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sp-ins__plan input').forEach(function (input) {
        input.addEventListener('change', function () {
            document.querySelectorAll('.sp-ins__plan').forEach(function (el) { el.classList.remove('is-active'); });
            input.closest('.sp-ins__plan').classList.add('is-active');
        });
    });
});
</script>
@endpush
