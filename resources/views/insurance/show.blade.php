@extends('frontend.layouts.app')

@section('title', ($insurance->name ?? 'بیمه').' — سپاسیران')

@section('content')
@php
    $tz = config('app.timezone', 'Asia/Tehran');
    $lede = \Illuminate\Support\Str::limit(strip_tags($insurance->description ?: 'پوشش مدت‌دار؛ مجوز در داشبورد.'), 110);
@endphp
<div class="sp-ins-m">
    <div class="sp-ins-m__stage">
        <div class="sp-ins-m__visual" style="--ins-photo:url('{{ $insurance->logoUrl() }}')">
            <div class="sp-ins-m__visual-photo" aria-hidden="true"></div>
            <div class="sp-ins-m__visual-copy">
                <p class="sp-ins-m__brand">سپاسیران</p>
                <h1>{{ $insurance->name }}</h1>
                <p>{{ $lede }}</p>
            </div>
        </div>

        <aside class="sp-ins-m__panel" id="insPlans">
            <a class="sp-ins-m__back" href="{{ route('frontend.clinic.insurance.index') }}">← فهرست بیمه‌ها</a>

            @if($errors->any())
                <p class="sp-ins-m__alert" role="alert">{{ $errors->first() }}</p>
            @endif

            @if($activeLicenses->isNotEmpty())
                <div class="sp-ins-m__active">
                    @foreach($activeLicenses->take(2) as $lic)
                        <span class="sp-ins-m__pill">
                            <strong class="num">{{ $lic->license_code }}</strong>
                            تا {{ optional($lic->ends_at)->timezone($tz)->format('Y/m/d') }}
                        </span>
                    @endforeach
                    <a href="{{ route('account.insurance-licenses.index') }}">مجوزها</a>
                </div>
            @endif

            <header class="sp-ins-m__panel-head">
                <h2>مدت پوشش</h2>
                <p>یک طرح را انتخاب کنید؛ مجوز همان مدت صادر می‌شود.</p>
            </header>

            @guest
                <a class="sp-ins-m__cta" href="{{ $loginUrl }}">ورود برای خرید</a>
            @else
                @if($plans->isEmpty())
                    <p class="sp-ins-m__empty">طرح فعالی نیست.</p>
                @else
                    <form method="post" action="{{ route('frontend.clinic.insurance.purchase', $insurance->id) }}" class="sp-ins-m__form">
                        @csrf
                        <input type="hidden" name="payment_method" value="wallet">

                        <div class="sp-ins-m__durations" role="radiogroup" aria-label="مدت پوشش">
                            @foreach($plans as $plan)
                                <label class="sp-ins-m__dur {{ (string) old('plan_id') === (string) $plan->id || ($loop->first && ! old('plan_id')) ? 'is-on' : '' }}">
                                    <input type="radio" name="plan_id" value="{{ $plan->id }}"
                                           @checked((string) old('plan_id') === (string) $plan->id || ($loop->first && ! old('plan_id')))
                                           required>
                                    <span class="sp-ins-m__dur-m"><span class="num">{{ $plan->duration_months }}</span> ماه</span>
                                    <span class="sp-ins-m__dur-n">{{ $plan->name }}</span>
                                    <span class="sp-ins-m__dur-p num">{{ number_format((int) $plan->price) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="sp-ins-m__field">
                            <label for="dog_id">سگ (اختیاری)</label>
                            <select name="dog_id" id="dog_id">
                                <option value="">—</option>
                                @foreach($dogs as $dog)
                                    <option value="{{ $dog->id }}" @selected((string) old('dog_id') === (string) $dog->id)>{{ $dog->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="sp-ins-m__cta">پرداخت از کیف پول</button>
                    </form>
                @endif
            @endguest

            @if($insurance->contact_phone || $insurance->contact_email)
                <p class="sp-ins-m__contact">
                    @if($insurance->contact_phone)<span class="num">{{ $insurance->contact_phone }}</span>@endif
                    @if($insurance->contact_phone && $insurance->contact_email) · @endif
                    @if($insurance->contact_email)<span>{{ $insurance->contact_email }}</span>@endif
                </p>
            @endif
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sp-ins-m__dur input').forEach(function (input) {
        input.addEventListener('change', function () {
            document.querySelectorAll('.sp-ins-m__dur').forEach(function (el) { el.classList.remove('is-on'); });
            input.closest('.sp-ins-m__dur').classList.add('is-on');
        });
    });
});
</script>
@endpush
