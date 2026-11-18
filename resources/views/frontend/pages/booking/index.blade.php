@extends('layouts.app')

@section('title', 'رزرو خدمت')

@section('content')
@php
    $authUser = auth()->user();
@endphp

<x-listing-hero
    title="رزرو خدمت"
    lede="گام‌به‌گام خدمت، زمان و تماس را مشخص کنید."
    icon="cta"
    image="images/demo/hero/hero-03.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'رزرو خدمت'],
    ]"
/>

<div class="wrap sp-booking" style="padding-top:28px;padding-bottom:56px">
    <div class="sp-booking__steps" role="list" id="bookingSteps">
        <div class="sp-booking__step is-current" data-step="1">۱. خدمت</div>
        <div class="sp-booking__step" data-step="2">۲. زمان</div>
        <div class="sp-booking__step" data-step="3">۳. تماس</div>
        <div class="sp-booking__step" data-step="4">۴. تأیید</div>
    </div>

    @if ($errors->any())
        <div class="panel" style="margin-bottom:16px;border-color:rgba(220,80,80,.35)">
            <ul style="margin:0;padding-right:18px;color:#f2b4b4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('frontend.booking.store') }}" id="bookingForm" class="sp-booking__layout">
        @csrf

        <div class="sp-booking__main">
            {{-- Step 1 --}}
            <section class="panel sp-booking__panel" data-panel="1">
                <h2>انتخاب خدمت</h2>
                <div class="sp-booking__service-grid">
                    @foreach($serviceLabels as $key => $label)
                        <label class="sp-booking__choice {{ $serviceType === $key ? 'is-active' : '' }}">
                            <input type="radio" name="service_type" value="{{ $key }}" {{ $serviceType === $key ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                            <small class="num">{{ number_format($defaultPrices[$key] ?? 0) }} تومان</small>
                        </label>
                    @endforeach
                </div>

                <div class="field" style="margin-top:18px">
                    <label for="provider_id">ارائه‌دهنده</label>
                    <select name="provider_id" id="provider_id" required>
                        <option value="">انتخاب کنید…</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}"
                                data-name="{{ $provider->name }}"
                                data-city="{{ $provider->city->name ?? '' }}"
                                {{ (int)$selectedProvider === (int)$provider->id ? 'selected' : '' }}>
                                {{ $provider->name }}@if($provider->city) — {{ $provider->city->name }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="city_id">شهر (اختیاری)</label>
                    <select name="city_id" id="city_id">
                        <option value="">—</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-primary" data-next="2">ادامه</button>
                </div>
            </section>

            {{-- Step 2 --}}
            <section class="panel sp-booking__panel" data-panel="2" hidden>
                <h2>انتخاب زمان</h2>
                <div class="field">
                    <label for="appointment_date">تاریخ</label>
                    <input class="num" type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', $prefillDate) }}" required>
                </div>
                <p class="sp-booking__hint">بازه زمانی</p>
                <div class="sp-booking__slots" id="slotGrid">
                    @foreach($slots as $slot)
                        <label class="chip sp-booking__slot {{ $prefillSlot === $slot ? 'is-active' : '' }}">
                            <input type="radio" name="slot" value="{{ $slot }}" {{ $prefillSlot === $slot ? 'checked' : '' }}>
                            <span class="num">{{ $slot }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="1">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="3">ادامه</button>
                </div>
            </section>

            {{-- Step 3 --}}
            <section class="panel sp-booking__panel" data-panel="3" hidden>
                <h2>اطلاعات تماس</h2>
                <div class="field">
                    <label for="contact_name">نام</label>
                    <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $authUser->name ?? '') }}" required>
                </div>
                <div class="field">
                    <label for="contact_phone">شماره تماس</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $authUser->phone ?? '') }}" required>
                </div>
                <div class="field">
                    <label for="notes">یادداشت</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="توضیح کوتاه…">{{ old('notes') }}</textarea>
                </div>
                <div class="field">
                    <label for="payment_method">روش پرداخت</label>
                    <select name="payment_method" id="payment_method">
                        <option value="online">آنلاین</option>
                        <option value="offline">حضوری / آفلاین</option>
                    </select>
                </div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="2">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="4">ادامه</button>
                </div>
            </section>

            {{-- Step 4 --}}
            <section class="panel sp-booking__panel" data-panel="4" hidden>
                <h2>تأیید رزرو</h2>
                <p class="sp-booking__hint">جزئیات را در خلاصه سمت چپ بررسی کنید، سپس ثبت کنید.</p>
                @guest
                    <p class="sp-booking__hint">برای ثبت نهایی باید وارد حساب شوید.</p>
                @endguest
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="3">بازگشت</button>
                    @auth
                        <button type="submit" class="btn btn-primary">ثبت رزرو</button>
                    @else
                        <a class="btn btn-primary" href="{{ route('login') }}?redirect={{ urlencode(route('frontend.booking.index')) }}">ورود و ثبت رزرو</a>
                    @endauth
                </div>
            </section>
        </div>

        <aside class="panel sp-booking__summary">
            <h3>خلاصه</h3>
            <div class="list-row"><span>خدمت</span><strong id="sumService">—</strong></div>
            <div class="list-row"><span>ارائه‌دهنده</span><strong id="sumProvider">—</strong></div>
            <div class="list-row"><span>تاریخ</span><strong class="num" id="sumDate">—</strong></div>
            <div class="list-row"><span>زمان</span><strong class="num" id="sumSlot">—</strong></div>
            <div class="list-row" style="border:0"><span>مبلغ</span><strong class="tile__price num" id="sumPrice">—</strong></div>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingForm');
    const prices = @json($defaultPrices);
    const labels = @json($serviceLabels);
    const providersUrl = @json(route('frontend.booking.providers'));
    let step = 1;

    function showStep(n) {
        step = n;
        document.querySelectorAll('[data-panel]').forEach(function (panel) {
            panel.hidden = Number(panel.dataset.panel) !== n;
        });
        document.querySelectorAll('.sp-booking__step').forEach(function (el) {
            const s = Number(el.dataset.step);
            el.classList.toggle('is-current', s === n);
            el.classList.toggle('is-done', s < n);
        });
        updateSummary();
    }

    function selectedService() {
        const el = form.querySelector('input[name="service_type"]:checked');
        return el ? el.value : 'coach';
    }

    function updateSummary() {
        const type = selectedService();
        const provider = form.querySelector('#provider_id');
        const opt = provider.options[provider.selectedIndex];
        const slot = form.querySelector('input[name="slot"]:checked');
        document.getElementById('sumService').textContent = labels[type] || '—';
        document.getElementById('sumProvider').textContent = (opt && opt.value) ? opt.dataset.name || opt.textContent : '—';
        document.getElementById('sumDate').textContent = form.appointment_date.value || '—';
        document.getElementById('sumSlot').textContent = slot ? slot.value : '—';
        document.getElementById('sumPrice').textContent = Number(prices[type] || 0).toLocaleString('fa-IR') + ' تومان';
    }

    async function reloadProviders() {
        const type = selectedService();
        const res = await fetch(providersUrl + '?type=' + encodeURIComponent(type), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        const select = form.querySelector('#provider_id');
        const current = select.value;
        select.innerHTML = '<option value="">انتخاب کنید…</option>';
        (data.results || []).forEach(function (row) {
            const opt = document.createElement('option');
            opt.value = row.id;
            opt.dataset.name = row.name;
            opt.dataset.city = row.city || '';
            opt.textContent = row.name + (row.city ? (' — ' + row.city) : '');
            if (String(row.id) === String(current)) opt.selected = true;
            select.appendChild(opt);
        });
        updateSummary();
    }

    form.querySelectorAll('input[name="service_type"]').forEach(function (input) {
        input.addEventListener('change', function () {
            form.querySelectorAll('.sp-booking__choice').forEach(function (c) { c.classList.remove('is-active'); });
            input.closest('.sp-booking__choice').classList.add('is-active');
            reloadProviders();
            updateSummary();
        });
    });

    form.querySelectorAll('#slotGrid .sp-booking__slot').forEach(function (chip) {
        chip.addEventListener('click', function () {
            form.querySelectorAll('#slotGrid .sp-booking__slot').forEach(function (c) { c.classList.remove('is-active'); });
            chip.classList.add('is-active');
            chip.querySelector('input').checked = true;
            updateSummary();
        });
    });

    form.querySelector('#provider_id').addEventListener('change', updateSummary);
    form.querySelector('#appointment_date').addEventListener('change', updateSummary);

    form.querySelectorAll('[data-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const next = Number(btn.dataset.next);
            if (next === 2 && !form.provider_id.value) {
                alert('ارائه‌دهنده را انتخاب کنید.');
                return;
            }
            if (next === 3 && (!form.appointment_date.value || !form.querySelector('input[name="slot"]:checked'))) {
                alert('تاریخ و بازه زمانی را انتخاب کنید.');
                return;
            }
            if (next === 4 && (!form.contact_name.value || !form.contact_phone.value)) {
                alert('نام و شماره تماس را وارد کنید.');
                return;
            }
            showStep(next);
        });
    });

    form.querySelectorAll('[data-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            showStep(Number(btn.dataset.prev));
        });
    });

    showStep(1);
});
</script>
@endpush