@extends('layouts.app')

@section('title', 'رزرو خدمت')

@section('content')
<x-listing-hero
    title="رزرو خدمت"
    lede="سگ خود را انتخاب کنید، شهر و خدمت را مشخص کنید، سپس مستقیم یا به‌صورت درخواست باز رزرو کنید."
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
     data-auth="{{ $isAuthenticated ? '1' : '0' }}"
     data-add-dog="{{ url('/breeder/dashboard-create') }}">
    <div class="sp-booking__steps" role="list" id="bookingSteps">
        <div class="sp-booking__step is-current" data-step="1">۱. سگ</div>
        <div class="sp-booking__step" data-step="2">۲. شهر</div>
        <div class="sp-booking__step" data-step="3">۳. خدمت</div>
        <div class="sp-booking__step" data-step="4">۴. نحوه</div>
        <div class="sp-booking__step" data-step="5">۵. جزئیات</div>
        <div class="sp-booking__step" data-step="6">۶. تأیید</div>
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
        <input type="hidden" name="dog_id" id="field_dog_id" value="">
        <input type="hidden" name="city_id" id="field_city_id" value="{{ $prefill['city_id'] ?? '' }}">
        <input type="hidden" name="service_type" id="field_service_type" value="{{ $prefill['service_type'] ?? 'coach' }}">
        <input type="hidden" name="training_type" id="field_training_type" value="">
        <input type="hidden" name="fulfillment_mode" id="field_fulfillment_mode" value="direct">
        <input type="hidden" name="provider_ids[]" id="field_provider_ids" value="{{ $prefill['provider_id'] ?? '' }}">
        <input type="hidden" name="preferred_date" id="field_preferred_date" value="{{ $prefill['date'] ?? '' }}">
        <input type="hidden" name="preferred_slot" id="field_preferred_slot" value="">

        <div class="sp-booking__main">
            <section class="panel sp-booking__panel" data-panel="1">
                <h2>سگ من</h2>
                @unless($isAuthenticated)
                    <p class="sp-booking__hint">برای ادامه باید وارد شوید — رزرو فقط برای سگ‌های ثبت‌شده در حساب شماست.</p>
                    <a class="btn btn-primary" href="{{ $loginUrl }}?redirect={{ urlencode(route('frontend.booking.index')) }}">ورود به حساب</a>
                @else
                    <div id="dogEmpty" class="sp-booking__empty" hidden>
                        <p>هنوز سگی در حساب شما ثبت نشده. ابتدا سگ خود را اضافه کنید یا از فرم زیر استفاده کنید.</p>
                        <a class="btn btn-ghost" id="addDogLink" href="{{ url('/breeder/dashboard-create') }}">صفحه افزودن سگ</a>
                    </div>
                    <div class="sp-booking__dog-grid" id="dogGrid"></div>
                    <details class="sp-booking__quick-dog" id="quickDogBox">
                        <summary>افزودن سریع سگ</summary>
                        <div class="field">
                            <label for="quick_dog_title">نام سگ</label>
                            <input type="text" id="quick_dog_title" maxlength="120" placeholder="مثلاً راکی">
                        </div>
                        <div class="field">
                            <label for="quick_dog_breed">نژاد</label>
                            <select id="quick_dog_breed"><option value="">—</option></select>
                        </div>
                        <div class="field">
                            <label for="quick_dog_age">سن (سال)</label>
                            <input class="num" type="number" id="quick_dog_age" min="0" max="40" placeholder="۳">
                        </div>
                        <button type="button" class="btn btn-primary" id="quickDogSubmit">ذخیره سگ</button>
                        <p class="sp-booking__hint" id="quickDogMsg" hidden></p>
                    </details>
                    <div class="sp-booking__actions" id="dogContinueWrap">
                        <button type="button" class="btn btn-primary" data-next="2" id="dogContinueBtn">ادامه</button>
                    </div>
                @endunless
            </section>

            <section class="panel sp-booking__panel" data-panel="2" hidden>
                <h2>انتخاب شهر</h2>
                <div class="field">
                    <label for="ui_city_q">جستجوی شهر</label>
                    <input type="search" id="ui_city_q" placeholder="نام شهر…" autocomplete="off">
                </div>
                <div class="field">
                    <label for="ui_city_id">شهر خدمت</label>
                    <select id="ui_city_id" required>
                        <option value="">انتخاب کنید…</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" @selected((int)($prefill['city_id'] ?? 0) === (int)$city->id)>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="1">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="3">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="3" hidden>
                <h2>انتخاب خدمت</h2>
                <div class="sp-booking__service-grid" id="serviceGrid"></div>
                <div class="field" id="coachExtras" hidden>
                    <label for="ui_training_type">نوع آموزش</label>
                    <select id="ui_training_type"></select>
                </div>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="2">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="4">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="4" hidden>
                <h2>نحوه انجام رزرو</h2>
                <div class="sp-booking__service-grid" id="modeGrid"></div>
                <p class="sp-booking__hint" id="modeHint"></p>
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="3">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="5">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="5" hidden>
                <h2>جزئیات رزرو</h2>

                <div id="directExtras">
                    <div class="field">
                        <label for="ui_provider_q">جستجوی ارائه‌دهنده</label>
                        <input type="search" id="ui_provider_q" placeholder="نام…">
                    </div>
                    <div class="sp-booking__provider-list" id="providerList"></div>
                    <div class="field">
                        <label for="ui_date">تاریخ ترجیحی</label>
                        <input class="num" type="date" id="ui_date" value="{{ $prefill['date'] ?? '' }}">
                    </div>
                    <p class="sp-booking__hint">بازه زمانی</p>
                    <div class="sp-booking__slots" id="slotGrid"></div>
                </div>

                <div id="openExtras" hidden>
                    <div class="field">
                        <label for="ui_open_date">بازه پیشنهادی (از تاریخ)</label>
                        <input class="num" type="date" id="ui_open_date" value="{{ $prefill['date'] ?? '' }}">
                    </div>
                    <p class="sp-booking__hint">درخواست شما برای ارائه‌دهندگان واجد شرایط منتشر می‌شود تا پیشنهاد بدهند.</p>
                </div>

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
                    <button type="button" class="btn btn-ghost" data-prev="4">بازگشت</button>
                    <button type="button" class="btn btn-primary" data-next="6">ادامه</button>
                </div>
            </section>

            <section class="panel sp-booking__panel" data-panel="6" hidden>
                <h2>تأیید و قیمت</h2>
                <div id="quoteBreakdown" class="sp-booking__quote"></div>
                @unless($isAuthenticated)
                    <p class="sp-booking__hint">برای ثبت نهایی باید وارد حساب شوید.</p>
                @endunless
                <div class="sp-booking__actions">
                    <button type="button" class="btn btn-ghost" data-prev="5">بازگشت</button>
                    @if($isAuthenticated)
                        <button type="button" class="btn btn-primary" id="submitBooking">ثبت رزرو</button>
                    @else
                        <a class="btn btn-primary" href="{{ $loginUrl }}?redirect={{ urlencode(route('frontend.booking.index')) }}">ورود و ثبت رزرو</a>
                    @endif
                </div>
            </section>
        </div>

        <aside class="panel sp-booking__summary">
            <h3>خلاصه</h3>
            <div class="list-row"><span>سگ</span><strong id="sumDog">—</strong></div>
            <div class="list-row"><span>شهر</span><strong id="sumCity">—</strong></div>
            <div class="list-row"><span>خدمت</span><strong id="sumService">—</strong></div>
            <div class="list-row"><span>نحوه</span><strong id="sumMode">—</strong></div>
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
    const csrf = root.dataset.csrf;
    const isAuth = root.dataset.auth === '1';
    const form = document.getElementById('bookingForm');
    let meta = { service_types: [], training_types: [], fulfillment_modes: [], pricing: {} };
    let step = 1;
    let dogs = [];
    let selectedProviders = {{ (int)($prefill['provider_id'] ?? 0) }} ? [{{ (int)($prefill['provider_id'] ?? 0) }}] : [];
    let providerNames = {};
    let quote = null;
    let labels = { service: {}, mode: {}, training: {} };
    let jalaliHint = '';

    async function api(path, options) {
        const res = await fetch(apiBase + path, Object.assign({
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        }, options || {}));
        const data = await res.json().catch(function () { return {}; });
        if (!res.ok) {
            const msg = (data.message) || (data.errors && Object.values(data.errors).flat().join(' ')) || ('خطا ' + res.status);
            throw new Error(msg);
        }
        return data;
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
        if (n === 5) toggleModeExtras();
        if (n === 6) refreshQuote();
        updateSummary();
    }

    function selectedService() {
        const el = form.querySelector('input[name="service_type_ui"]:checked');
        return el ? el.value : (document.getElementById('field_service_type').value || 'coach');
    }

    function selectedMode() {
        const el = form.querySelector('input[name="mode_ui"]:checked');
        return el ? el.value : (document.getElementById('field_fulfillment_mode').value || 'direct');
    }

    function selectedDogId() {
        const el = form.querySelector('input[name="dog_ui"]:checked');
        return el ? el.value : '';
    }

    function syncHidden() {
        document.getElementById('field_dog_id').value = selectedDogId();
        document.getElementById('field_city_id').value = document.getElementById('ui_city_id').value || '';
        document.getElementById('field_service_type').value = selectedService();
        document.getElementById('field_training_type').value = document.getElementById('ui_training_type').value || '';
        document.getElementById('field_fulfillment_mode').value = selectedMode();
        const dateInput = selectedMode() === 'open'
            ? document.getElementById('ui_open_date')
            : document.getElementById('ui_date');
        document.getElementById('field_preferred_date').value = dateInput.value || '';
        const slot = form.querySelector('input[name="slot_ui"]:checked');
        document.getElementById('field_preferred_slot').value = selectedMode() === 'direct' && slot ? slot.value : '';
        // rebuild provider_ids hidden inputs
        form.querySelectorAll('input[name="provider_ids[]"]').forEach(function (n) { n.remove(); });
        if (selectedMode() === 'direct') {
            selectedProviders.forEach(function (id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'provider_ids[]';
                input.value = id;
                form.appendChild(input);
            });
        }
    }

    function toggleModeExtras() {
        const open = selectedMode() === 'open';
        document.getElementById('directExtras').hidden = open;
        document.getElementById('openExtras').hidden = !open;
        document.getElementById('modeHint').textContent = open
            ? 'درخواست باز: ارائه‌دهندگان واجد شرایط پیشنهاد می‌فرستند و شما یکی را می‌پذیرید.'
            : 'ارسال مستقیم: درخواست برای ارائه‌دهنده(های) انتخاب‌شده ارسال می‌شود.';
    }

    function updateSummary() {
        syncHidden();
        const dog = dogs.find(function (d) { return String(d.id) === String(selectedDogId()); });
        const citySel = document.getElementById('ui_city_id');
        const cityOpt = citySel.options[citySel.selectedIndex];
        document.getElementById('sumDog').textContent = dog ? (dog.title + (dog.breed_name ? ' — ' + dog.breed_name : '')) : '—';
        document.getElementById('sumCity').textContent = (cityOpt && cityOpt.value) ? cityOpt.textContent : '—';
        document.getElementById('sumService').textContent = labels.service[selectedService()] || '—';
        document.getElementById('sumMode').textContent = labels.mode[selectedMode()] || '—';
        if (selectedMode() === 'open') {
            document.getElementById('sumProvider').textContent = 'درخواست باز';
        } else if (!selectedProviders.length) {
            document.getElementById('sumProvider').textContent = '—';
        } else {
            document.getElementById('sumProvider').textContent = selectedProviders
                .map(function (id) { return providerNames[id] || ('#' + id); })
                .join('، ');
        }
        const gDate = document.getElementById('field_preferred_date').value || '';
        document.getElementById('sumDate').textContent = jalaliHint || gDate || '—';
        document.getElementById('sumSlot').textContent = document.getElementById('field_preferred_slot').value || '—';
        const total = quote ? quote.total : 0;
        document.getElementById('sumPrice').textContent = total
            ? Number(total).toLocaleString('fa-IR') + ' تومان'
            : '—';
        document.getElementById('coachExtras').hidden = selectedService() !== 'coach';
    }

    function renderDogs() {
        const grid = document.getElementById('dogGrid');
        const empty = document.getElementById('dogEmpty');
        const continueWrap = document.getElementById('dogContinueWrap');
        if (!grid) return;
        grid.innerHTML = '';
        if (!dogs.length) {
            empty.hidden = false;
            if (continueWrap) continueWrap.hidden = true;
            return;
        }
        empty.hidden = true;
        if (continueWrap) continueWrap.hidden = false;
        dogs.forEach(function (dog, idx) {
            const label = document.createElement('label');
            label.className = 'sp-booking__choice' + (idx === 0 ? ' is-active' : '');
            label.innerHTML = '<input type="radio" name="dog_ui" value="' + dog.id + '"' + (idx === 0 ? ' checked' : '') + '>' +
                '<span>' + (dog.title || ('#' + dog.id)) + '</span>' +
                '<small>' + (dog.breed_name || 'بدون نژاد') + '</small>';
            grid.appendChild(label);
        });
        grid.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('change', function () {
                grid.querySelectorAll('.sp-booking__choice').forEach(function (c) { c.classList.remove('is-active'); });
                input.closest('.sp-booking__choice').classList.add('is-active');
                updateSummary();
            });
        });
        updateSummary();
    }

    function renderServices() {
        const grid = document.getElementById('serviceGrid');
        grid.innerHTML = '';
        meta.service_types.forEach(function (row) {
            labels.service[row.key] = row.label;
            const label = document.createElement('label');
            label.className = 'sp-booking__choice' + (selectedService() === row.key ? ' is-active' : '');
            label.innerHTML = '<input type="radio" name="service_type_ui" value="' + row.key + '"' +
                (selectedService() === row.key ? ' checked' : '') + '>' +
                '<span>' + row.label + '</span><small class="num">' +
                Number(row.default_price).toLocaleString('fa-IR') + ' تومان</small>';
            grid.appendChild(label);
        });
        grid.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('change', function () {
                grid.querySelectorAll('.sp-booking__choice').forEach(function (c) { c.classList.remove('is-active'); });
                input.closest('.sp-booking__choice').classList.add('is-active');
                selectedProviders = [];
                reloadProviders();
                updateSummary();
            });
        });
    }

    function renderModes() {
        const grid = document.getElementById('modeGrid');
        grid.innerHTML = '';
        (meta.fulfillment_modes || []).forEach(function (row) {
            labels.mode[row.key] = row.label;
            const label = document.createElement('label');
            label.className = 'sp-booking__choice' + (selectedMode() === row.key ? ' is-active' : '');
            label.innerHTML = '<input type="radio" name="mode_ui" value="' + row.key + '"' +
                (selectedMode() === row.key ? ' checked' : '') + '>' +
                '<span>' + row.label + '</span>';
            grid.appendChild(label);
        });
        grid.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('change', function () {
                grid.querySelectorAll('.sp-booking__choice').forEach(function (c) { c.classList.remove('is-active'); });
                input.closest('.sp-booking__choice').classList.add('is-active');
                toggleModeExtras();
                updateSummary();
            });
        });
    }

    function renderTraining() {
        const sel = document.getElementById('ui_training_type');
        sel.innerHTML = '<option value="">—</option>';
        meta.training_types.forEach(function (row) {
            labels.training[row.key] = row.label;
            const opt = document.createElement('option');
            opt.value = row.key;
            opt.textContent = row.label + (row.surcharge ? (' (+' + Number(row.surcharge).toLocaleString('fa-IR') + ')') : '');
            sel.appendChild(opt);
        });
        sel.addEventListener('change', updateSummary);
    }

    async function reloadProviders() {
        if (!isAuth) return;
        const type = selectedService();
        const cityId = document.getElementById('ui_city_id').value;
        const q = document.getElementById('ui_provider_q').value || '';
        let path = '/providers?service_type=' + encodeURIComponent(type);
        if (cityId) path += '&city_id=' + encodeURIComponent(cityId);
        if (q) path += '&q=' + encodeURIComponent(q);
        const data = await api(path);
        const list = document.getElementById('providerList');
        list.innerHTML = '';
        (data.results || []).forEach(function (row) {
            providerNames[row.id] = row.display_name || row.name;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'sp-booking__provider' + (selectedProviders.indexOf(row.id) >= 0 ? ' is-active' : '');
            btn.dataset.id = row.id;
            btn.innerHTML = '<strong>' + (row.display_name || row.name) + '</strong>' +
                '<span>' + (row.city ? row.city.name : '—') + '</span>';
            btn.addEventListener('click', function () {
                const id = Number(row.id);
                const idx = selectedProviders.indexOf(id);
                if (idx >= 0) selectedProviders.splice(idx, 1);
                else selectedProviders.push(id);
                reloadProviders();
                reloadSlots();
                updateSummary();
            });
            list.appendChild(btn);
        });
        updateSummary();
    }

    async function reloadSlots() {
        const providerId = selectedProviders[0];
        const date = document.getElementById('ui_date').value;
        const grid = document.getElementById('slotGrid');
        grid.innerHTML = '';
        jalaliHint = '';
        if (!providerId || !date || selectedMode() !== 'direct') return;
        const data = await api('/slots?provider_id=' + encodeURIComponent(providerId) + '&date=' + encodeURIComponent(date));
        jalaliHint = data.date_jalali || '';
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
    }

    async function refreshQuote() {
        const box = document.getElementById('quoteBreakdown');
        box.innerHTML = '<p class="sp-booking__hint">در حال محاسبه…</p>';
        try {
            quote = await api('/quote', {
                method: 'POST',
                body: JSON.stringify({
                    service_type: selectedService(),
                    fulfillment_mode: selectedMode(),
                    training_type: document.getElementById('ui_training_type').value || null
                })
            });
            box.innerHTML = '';
            (quote.breakdown || []).forEach(function (line) {
                const row = document.createElement('div');
                row.className = 'list-row';
                row.innerHTML = '<span>' + line.label + '</span><strong class="num">' +
                    Number(line.amount).toLocaleString('fa-IR') + ' تومان</strong>';
                box.appendChild(row);
            });
            const total = document.createElement('div');
            total.className = 'list-row';
            total.style.border = '0';
            total.innerHTML = '<span>جمع</span><strong class="tile__price num">' +
                Number(quote.total).toLocaleString('fa-IR') + ' تومان</strong>';
            box.appendChild(total);
            updateSummary();
        } catch (e) {
            box.innerHTML = '<p class="sp-booking__hint">' + e.message + '</p>';
        }
    }

    form.querySelectorAll('[data-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            syncHidden();
            const next = Number(btn.dataset.next);
            if (next === 2 && !selectedDogId()) { alert('سگ خود را انتخاب کنید.'); return; }
            if (next === 3 && !document.getElementById('field_city_id').value) { alert('شهر را انتخاب کنید.'); return; }
            if (next === 4 && selectedService() === 'coach' && !document.getElementById('field_training_type').value) {
                alert('نوع آموزش را انتخاب کنید.'); return;
            }
            if (next === 6 && selectedMode() === 'direct' && !selectedProviders.length) {
                alert('حداقل یک ارائه‌دهنده را انتخاب کنید.'); return;
            }
            if (next === 6 && (!form.contact_name.value || !form.contact_phone.value)) {
                alert('نام و شماره تماس را وارد کنید.'); return;
            }
            showStep(next);
        });
    });
    form.querySelectorAll('[data-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () { showStep(Number(btn.dataset.prev)); });
    });

    const submitBtn = document.getElementById('submitBooking');
    if (submitBtn) {
        submitBtn.addEventListener('click', async function () {
            syncHidden();
            submitBtn.disabled = true;
            try {
                const payload = {
                    dog_id: Number(document.getElementById('field_dog_id').value),
                    city_id: Number(document.getElementById('field_city_id').value),
                    service_type: selectedService(),
                    training_type: document.getElementById('field_training_type').value || null,
                    fulfillment_mode: selectedMode(),
                    provider_ids: selectedMode() === 'direct' ? selectedProviders : [],
                    preferred_date: document.getElementById('field_preferred_date').value || null,
                    preferred_slot: document.getElementById('field_preferred_slot').value || null,
                    contact_name: form.contact_name.value,
                    contact_phone: form.contact_phone.value,
                    notes: form.notes.value || null,
                    payment_method: form.payment_method.value
                };
                const data = await api('', { method: 'POST', body: JSON.stringify(payload) });
                window.location.href = data.success_url || ('/booking/success/' + data.booking.booking_code);
            } catch (e) {
                alert(e.message);
                submitBtn.disabled = false;
            }
        });
    }

    document.getElementById('ui_city_id').addEventListener('change', function () { updateSummary(); reloadProviders(); });
    document.getElementById('ui_date').addEventListener('change', function () { updateSummary(); reloadSlots(); });
    document.getElementById('ui_open_date').addEventListener('change', updateSummary);
    let providerTimer = null;
    document.getElementById('ui_provider_q').addEventListener('input', function () {
        clearTimeout(providerTimer);
        providerTimer = setTimeout(reloadProviders, 280);
    });

    let cityTimer = null;
    const citySearch = document.getElementById('ui_city_q');
    if (citySearch) {
        citySearch.addEventListener('input', function () {
            clearTimeout(cityTimer);
            cityTimer = setTimeout(async function () {
                const q = citySearch.value.trim();
                const data = await api('/cities?q=' + encodeURIComponent(q));
                const sel = document.getElementById('ui_city_id');
                const current = sel.value;
                sel.innerHTML = '<option value="">انتخاب کنید…</option>';
                (data.results || []).forEach(function (row) {
                    const opt = document.createElement('option');
                    opt.value = row.id;
                    opt.textContent = row.name;
                    if (String(row.id) === String(current)) opt.selected = true;
                    sel.appendChild(opt);
                });
            }, 280);
        });
    }

    async function loadBreedsForQuick() {
        const sel = document.getElementById('quick_dog_breed');
        if (!sel) return;
        const data = await api('/breeds');
        sel.innerHTML = '<option value="">—</option>';
        (data.results || []).forEach(function (row) {
            const opt = document.createElement('option');
            opt.value = row.id;
            opt.textContent = row.name;
            sel.appendChild(opt);
        });
    }

    const quickSubmit = document.getElementById('quickDogSubmit');
    if (quickSubmit) {
        quickSubmit.addEventListener('click', async function () {
            const title = document.getElementById('quick_dog_title').value.trim();
            const msg = document.getElementById('quickDogMsg');
            if (!title) {
                msg.hidden = false;
                msg.textContent = 'نام سگ الزامی است.';
                return;
            }
            quickSubmit.disabled = true;
            try {
                const ageVal = document.getElementById('quick_dog_age').value;
                const data = await api('/my-dogs', {
                    method: 'POST',
                    body: JSON.stringify({
                        title: title,
                        breed_id: document.getElementById('quick_dog_breed').value || null,
                        age: ageVal === '' ? null : Number(ageVal)
                    })
                });
                dogs.unshift(data.dog);
                document.getElementById('quick_dog_title').value = '';
                document.getElementById('quick_dog_age').value = '';
                msg.hidden = false;
                msg.textContent = 'سگ ذخیره شد.';
                renderDogs();
            } catch (e) {
                msg.hidden = false;
                msg.textContent = e.message;
            }
            quickSubmit.disabled = false;
        });
    }

    (async function boot() {
        meta = await api('/meta');
        if (meta.add_dog_url) {
            const link = document.getElementById('addDogLink');
            if (link) link.href = meta.add_dog_url;
            root.dataset.addDog = meta.add_dog_url;
        }
        renderServices();
        renderModes();
        renderTraining();
        if (isAuth) {
            await loadBreedsForQuick();
            const mine = await api('/my-dogs');
            dogs = mine.results || [];
            if (mine.add_dog_url) {
                const link = document.getElementById('addDogLink');
                if (link) link.href = mine.add_dog_url;
            }
            renderDogs();
            await reloadProviders();
        }
        showStep(1);
    })().catch(function (e) {
        console.error(e);
        alert('بارگذاری اطلاعات رزرو ناموفق بود.');
    });
});
</script>
@endpush
