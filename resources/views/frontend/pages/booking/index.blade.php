@extends('layouts.app')

@section('title', 'رزرو خدمت')

@section('content')
<x-listing-hero
    title="رزرو خدمت"
    lede="گام‌به‌گام خدمت، ارائه‌دهنده، زمان و تماس را مشخص کنید."
    icon="cta"
    image="images/demo/hero/hero-03.jpg"
    :crumbs="[
        ['label' => 'خانه', 'route' => route('frontend.home')],
        ['label' => 'رزرو خدمت'],
    ]"
/>

<div class="wrap sp-booking" style="padding-top:28px;padding-bottom:56px"
     data-api-base="{{ $apiBase }}"
     data-csrf="{{ $csrf }}"
     data-login="{{ $loginUrl }}"
     data-auth="{{ $isAuthenticated ? '1' : '0' }}">
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
        <input type="hidden" name="service_type" id="field_service_type" value="{{ $prefill['service_type'] ?? 'coach' }}">
        <input type="hidden" name="provider_id" id="field_provider_id" value="{{ $prefill['provider_id'] ?? '' }}">
        <input type="hidden" name="city_id" id="field_city_id" value="{{ $prefill['city_id'] ?? '' }}">
        <input type="hidden" name="breed_id" id="field_breed_id" value="">
        <input type="hidden" name="training_type" id="field_training_type" value="">
        <input type="hidden" name="date" id="field_date" value="{{ $prefill['date'] ?? '' }}">
        <input type="hidden" name="slot" id="field_slot" value="">

        <div class="sp-booking__main">
            <section class="panel sp-booking__panel" data-panel="1">
                <h2>انتخاب خدمت و ارائه‌دهنده</h2>
                <div class="sp-booking__service-grid" id="serviceGrid"></div>
                <div class="field" id="coachExtras" hidden>
                    <label for="ui_training_type">نوع آموزش</label>
                    <select id="ui_training_type"></select>
                    <label for="ui_breed_id" style="margin-top:12px;display:block">نژاد سگ</label>
                    <select id="ui_breed_id"></select>
                </div>
                <div class="field" style="margin-top:18px">
                    <label for="ui_city_id">شهر (اختیاری)</label>
                    <select id="ui_city_id">
                        <option value="">—</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" @selected((int)($prefill['city_id'] ?? 0) === (int)$city->id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="ui_provider_id">ارائه‌دهنده</label>
                    <select id="ui_provider_id" required>
                        <option value="">انتخاب کنید…</option>
                    </select>
                </div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-primary" data-next="2">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="2" hidden>
                <h2>انتخاب زمان</h2>
                <div class="field">
                    <label for="ui_date">تاریخ (گرگوری برای API — UI جلالی در Vue)</label>
                    <input class="num" type="date" id="ui_date" value="{{ $prefill['date'] ?? '' }}" required>
                </div>
                <p class="sp-booking__hint">بازه زمانی</p>
                <div class="sp-booking__slots" id="slotGrid"></div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="1">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="3">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="3" hidden>
                <h2>اطلاعات تماس</h2>
                <div class="field">
                    <label for="contact_name">نام</label>
                    <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', optional($authUser)->name) }}" required>
                </div>
                <div class="field">
                    <label for="contact_phone">شماره تماس</label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', optional($authUser)->phone) }}" required>
                </div>
                <div class="field">
                    <label for="notes">یادداشت</label>
                    <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
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

            <section class="panel sp-booking__panel" data-panel="4" hidden>
                <h2>تأیید رزرو</h2>
                <p class="sp-booking__hint">جزئیات را در خلاصه بررسی کنید، سپس ثبت کنید.</p>
                @unless($isAuthenticated)
                    <p class="sp-booking__hint">برای ثبت نهایی باید وارد حساب شوید.</p>
                @endunless
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="3">بازگشت</button>
                    @if($isAuthenticated)
                        <button type="submit" class="btn btn-primary">ثبت رزرو</button>
                    @else
                        <a class="btn btn-primary" href="{{ $loginUrl }}?redirect={{ urlencode(route('frontend.booking.index')) }}">ورود و ثبت رزرو</a>
                    @endif
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
    const root = document.querySelector('.sp-booking');
    const apiBase = root.dataset.apiBase;
    const form = document.getElementById('bookingForm');
    let meta = { service_types: [], training_types: [], slot_template: [] };
    let step = 1;
    let prices = {};
    let labels = {};

    async function api(path) {
        const res = await fetch(apiBase + path, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error('api ' + res.status);
        return res.json();
    }

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

    function syncHidden() {
        document.getElementById('field_service_type').value = selectedService();
        document.getElementById('field_provider_id').value = document.getElementById('ui_provider_id').value || '';
        document.getElementById('field_city_id').value = document.getElementById('ui_city_id').value || '';
        document.getElementById('field_breed_id').value = document.getElementById('ui_breed_id').value || '';
        document.getElementById('field_training_type').value = document.getElementById('ui_training_type').value || '';
        document.getElementById('field_date').value = document.getElementById('ui_date').value || '';
        const slot = form.querySelector('input[name="slot_ui"]:checked');
        document.getElementById('field_slot').value = slot ? slot.value : '';
    }

    function selectedService() {
        const el = form.querySelector('input[name="service_type_ui"]:checked');
        return el ? el.value : (document.getElementById('field_service_type').value || 'coach');
    }

    function updateSummary() {
        syncHidden();
        const type = selectedService();
        const provider = document.getElementById('ui_provider_id');
        const opt = provider.options[provider.selectedIndex];
        document.getElementById('sumService').textContent = labels[type] || '—';
        document.getElementById('sumProvider').textContent = (opt && opt.value) ? (opt.dataset.name || opt.textContent) : '—';
        document.getElementById('sumDate').textContent = document.getElementById('ui_date').value || '—';
        document.getElementById('sumSlot').textContent = document.getElementById('field_slot').value || '—';
        document.getElementById('sumPrice').textContent = Number(prices[type] || 0).toLocaleString('fa-IR') + ' تومان';
        document.getElementById('coachExtras').hidden = type !== 'coach';
    }

    function renderServices() {
        const grid = document.getElementById('serviceGrid');
        grid.innerHTML = '';
        meta.service_types.forEach(function (row) {
            const label = document.createElement('label');
            label.className = 'sp-booking__choice' + (selectedService() === row.key ? ' is-active' : '');
            label.innerHTML = '<input type="radio" name="service_type_ui" value="' + row.key + '"' +
                (selectedService() === row.key ? ' checked' : '') + '>' +
                '<span>' + row.label + '</span><small class="num">' +
                Number(row.default_price).toLocaleString('fa-IR') + ' تومان</small>';
            grid.appendChild(label);
            prices[row.key] = row.default_price;
            labels[row.key] = row.label;
        });
        grid.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('change', function () {
                grid.querySelectorAll('.sp-booking__choice').forEach(function (c) { c.classList.remove('is-active'); });
                input.closest('.sp-booking__choice').classList.add('is-active');
                reloadProviders();
                updateSummary();
            });
        });
    }

    function renderTraining() {
        const sel = document.getElementById('ui_training_type');
        sel.innerHTML = '<option value="">—</option>';
        meta.training_types.forEach(function (row) {
            const opt = document.createElement('option');
            opt.value = row.key;
            opt.textContent = row.label;
            sel.appendChild(opt);
        });
        sel.addEventListener('change', updateSummary);
    }

    async function reloadBreeds() {
        const data = await api('/breeds');
        const sel = document.getElementById('ui_breed_id');
        sel.innerHTML = '<option value="">—</option>';
        (data.results || []).forEach(function (row) {
            const opt = document.createElement('option');
            opt.value = row.id;
            opt.textContent = row.name;
            sel.appendChild(opt);
        });
        sel.addEventListener('change', updateSummary);
    }

    async function reloadProviders() {
        const type = selectedService();
        const cityId = document.getElementById('ui_city_id').value;
        let path = '/providers?service_type=' + encodeURIComponent(type);
        if (cityId) path += '&city_id=' + encodeURIComponent(cityId);
        const data = await api(path);
        const select = document.getElementById('ui_provider_id');
        const current = select.value || String({{ (int)($prefill['provider_id'] ?? 0) }});
        select.innerHTML = '<option value="">انتخاب کنید…</option>';
        (data.results || []).forEach(function (row) {
            const opt = document.createElement('option');
            opt.value = row.id;
            opt.dataset.name = row.display_name || row.name;
            opt.textContent = (row.display_name || row.name) + (row.city ? (' — ' + row.city.name) : '');
            if (String(row.id) === String(current)) opt.selected = true;
            select.appendChild(opt);
        });
        updateSummary();
        reloadSlots();
    }

    async function reloadSlots() {
        const providerId = document.getElementById('ui_provider_id').value;
        const date = document.getElementById('ui_date').value;
        const grid = document.getElementById('slotGrid');
        grid.innerHTML = '';
        if (!providerId || !date) return;
        const data = await api('/slots?provider_id=' + encodeURIComponent(providerId) + '&date=' + encodeURIComponent(date));
        (data.slots || []).forEach(function (slot) {
            const label = document.createElement('label');
            label.className = 'chip sp-booking__slot' + (slot.available ? '' : ' is-disabled');
            if (!slot.available) label.style.opacity = '0.4';
            label.innerHTML = '<input type="radio" name="slot_ui" value="' + slot.time + '"' +
                (slot.available ? '' : ' disabled') + '><span class="num">' + slot.time + '</span>';
            grid.appendChild(label);
        });
        grid.querySelectorAll('.sp-booking__slot').forEach(function (chip) {
            chip.addEventListener('click', function () {
                if (chip.classList.contains('is-disabled')) return;
                grid.querySelectorAll('.sp-booking__slot').forEach(function (c) { c.classList.remove('is-active'); });
                chip.classList.add('is-active');
                chip.querySelector('input').checked = true;
                updateSummary();
            });
        });
        updateSummary();
    }

    form.querySelectorAll('[data-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            syncHidden();
            const next = Number(btn.dataset.next);
            if (next === 2 && !document.getElementById('field_provider_id').value) {
                alert('ارائه‌دهنده را انتخاب کنید.');
                return;
            }
            if (next === 2 && selectedService() === 'coach' && (!document.getElementById('field_breed_id').value || !document.getElementById('field_training_type').value)) {
                alert('برای مربی، نژاد و نوع آموزش را انتخاب کنید.');
                return;
            }
            if (next === 3 && (!document.getElementById('field_date').value || !document.getElementById('field_slot').value)) {
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
        btn.addEventListener('click', function () { showStep(Number(btn.dataset.prev)); });
    });

    document.getElementById('ui_city_id').addEventListener('change', reloadProviders);
    document.getElementById('ui_provider_id').addEventListener('change', function () { updateSummary(); reloadSlots(); });
    document.getElementById('ui_date').addEventListener('change', function () { updateSummary(); reloadSlots(); });
    form.addEventListener('submit', syncHidden);

    (async function boot() {
        meta = await api('/meta');
        renderServices();
        renderTraining();
        await reloadBreeds();
        await reloadProviders();
        showStep(1);
    })().catch(function (e) {
        console.error(e);
        alert('بارگذاری اطلاعات رزرو ناموفق بود.');
    });
});
</script>
@endpush
