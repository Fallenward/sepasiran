@extends('layouts.app')

@section('title', 'رزرو ثبت شد')

@section('content')
@php
    $code = $booking['booking_code'] ?? '';
    $faCode = preg_replace_callback('/\d/', fn ($m) => ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'][(int)$m[0]], $code);
    $serviceLabel = $serviceLabels[$booking['service_type'] ?? ''] ?? ($booking['service_type'] ?? '—');
    $modeLabel = ($modeLabels[$booking['fulfillment_mode'] ?? ''] ?? null)
        ?? (($booking['fulfillment_mode'] ?? '') === 'open' ? 'درخواست باز' : 'ارسال مستقیم');
    $status = $booking['status'] ?? '';
    $isOpen = $status === 'OPEN';
    $canCancel = in_array($status, ['PENDING', 'OPEN', 'ACCEPTED'], true);
    $title = $isOpen ? 'درخواست باز ثبت شد' : ($status === 'ACCEPTED' ? 'پیشنهاد پذیرفته شد' : 'رزرو ثبت شد');
    $lede = $isOpen
        ? 'درخواست شما منتشر شد؛ ارائه‌دهندگان واجد شرایط می‌توانند پیشنهاد بفرستند.'
        : 'رزرو شما ثبت شد و در انتظار پیگیری است.';
    $offers = $booking['offers'] ?? [];
@endphp
<div class="wrap sp-booking-success" style="padding:40px 0 64px;max-width:720px"
     data-api-base="{{ url('/booking/api') }}"
     data-csrf="{{ csrf_token() }}"
     data-code="{{ $code }}"
     data-status="{{ $status }}">
    <div class="panel" style="text-align:center">
        <div class="sp-section-head__icon" style="margin:0 auto 16px;width:56px;height:56px;border-radius:16px">
            <x-sp-icon name="trust" />
        </div>
        <h1 style="margin:0 0 8px;font-size:1.6rem">{{ $title }}</h1>
        <p style="color:var(--sp-text-muted);margin:0 0 10px">{{ $lede }}</p>
        <p style="color:var(--sp-text-muted);margin:0 0 18px">کد رزرو شما:</p>
        <p class="num" style="font-size:1.8rem;font-weight:800;color:var(--sp-green);margin:0 0 24px">{{ $faCode }}</p>

        <div style="text-align:right">
            <div class="list-row"><span>سگ</span><strong>{{ $booking['dog']['title'] ?? '—' }}</strong></div>
            <div class="list-row"><span>شهر</span><strong>{{ $booking['city']['name'] ?? '—' }}</strong></div>
            <div class="list-row"><span>خدمت</span><strong>{{ $serviceLabel }}</strong></div>
            <div class="list-row"><span>نحوه</span><strong>{{ $modeLabel }}</strong></div>
            <div class="list-row"><span>وضعیت</span><strong id="successStatus">{{ $status ?: '—' }}</strong></div>
            <div class="list-row"><span>ارائه‌دهنده</span><strong id="successProvider">{{ $booking['provider']['display_name'] ?? ($isOpen ? 'در انتظار پیشنهاد' : '—') }}</strong></div>
            @if(!empty($booking['breed']['name']) || !empty($booking['dog']['breed_name']))
                <div class="list-row"><span>نژاد</span><strong>{{ $booking['breed']['name'] ?? $booking['dog']['breed_name'] }}</strong></div>
            @endif
            <div class="list-row"><span>تاریخ</span><strong class="num">{{ $booking['appointment_jalali'] ?? $booking['preferred_jalali'] ?? '—' }}</strong></div>
            <div class="list-row"><span>ساعت</span><strong class="num">{{ $booking['slot'] ?? $booking['preferred_slot'] ?? '—' }}</strong></div>
            <div class="list-row" style="border:0"><span>مبلغ</span><strong class="num" id="successTotal">{{ number_format((float) ($booking['total'] ?? 0)) }} تومان</strong></div>
        </div>

        @if(($booking['fulfillment_mode'] ?? '') === 'open')
            <p style="margin:18px 0 0;text-align:right">
                <a class="sp-btn sp-btn--primary" href="{{ route('frontend.booking.open.show', $booking['booking_code']) }}">مدیریت در صفحه درخواست‌های باز</a>
                <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.booking.open') }}">همه درخواست‌های باز</a>
            </p>
            <div class="sp-booking__offers" id="offersBox" style="text-align:right;margin-top:22px">
                <h3 style="margin:0 0 12px;font-size:1.05rem">پیشنهادها</h3>
                <div id="offersList">
                    @forelse($offers as $offer)
                        <div class="sp-booking__offer" data-offer-id="{{ $offer['id'] }}">
                            <div>
                                <strong>{{ $offer['provider']['display_name'] ?? 'ارائه‌دهنده' }}</strong>
                                <span class="num">{{ number_format((float) ($offer['price'] ?? 0)) }} تومان</span>
                                @if(!empty($offer['message']))
                                    <p class="sp-booking__hint" style="margin:6px 0 0">{{ $offer['message'] }}</p>
                                @endif
                                <p class="sp-booking__hint num" style="margin:4px 0 0">
                                    {{ $offer['proposed_date'] ?? '—' }}
                                    @if(!empty($offer['proposed_slot'])) · {{ $offer['proposed_slot'] }} @endif
                                    · {{ $offer['status'] }}
                                </p>
                            </div>
                            @if($isOpen && ($offer['status'] ?? '') === 'pending')
                                <button type="button" class="btn btn-primary btn-sm" data-accept-offer="{{ $offer['id'] }}">پذیرش</button>
                            @endif
                        </div>
                    @empty
                        <p class="sp-booking__hint" id="offersEmpty">هنوز پیشنهادی ثبت نشده. این صفحه را بعداً تازه کنید.</p>
                    @endforelse
                </div>
                <button type="button" class="btn btn-ghost" id="refreshOffers" style="margin-top:12px">بروزرسانی پیشنهادها</button>
            </div>
        @endif

        <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-top:22px">
            @if($canCancel)
                <button type="button" class="btn btn-ghost" id="cancelBooking">لغو رزرو</button>
            @endif
            <a class="btn btn-primary" href="{{ route('frontend.booking.index') }}">رزرو جدید</a>
            <a class="btn btn-ghost" href="{{ route('frontend.home') }}">خانه</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('.sp-booking-success');
    if (!root) return;
    const apiBase = root.dataset.apiBase;
    const csrf = root.dataset.csrf;
    const code = root.dataset.code;

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
            const msg = data.message || (data.errors && Object.values(data.errors).flat().join(' ')) || ('خطا ' + res.status);
            throw new Error(msg);
        }
        return data;
    }

    function renderOffers(results) {
        const list = document.getElementById('offersList');
        if (!list) return;
        const pendingAccept = root.dataset.status === 'OPEN';
        if (!results.length) {
            list.innerHTML = '<p class="sp-booking__hint">هنوز پیشنهادی ثبت نشده. این صفحه را بعداً تازه کنید.</p>';
            return;
        }
        list.innerHTML = '';
        results.forEach(function (offer) {
            const row = document.createElement('div');
            row.className = 'sp-booking__offer';
            row.dataset.offerId = offer.id;
            let html = '<div><strong>' + ((offer.provider && offer.provider.display_name) || 'ارائه‌دهنده') + '</strong>' +
                '<span class="num">' + Number(offer.price || 0).toLocaleString('fa-IR') + ' تومان</span>';
            if (offer.message) html += '<p class="sp-booking__hint" style="margin:6px 0 0">' + offer.message + '</p>';
            html += '<p class="sp-booking__hint num" style="margin:4px 0 0">' +
                (offer.proposed_date || '—') +
                (offer.proposed_slot ? (' · ' + offer.proposed_slot) : '') +
                ' · ' + offer.status + '</p></div>';
            if (pendingAccept && offer.status === 'pending') {
                html += '<button type="button" class="btn btn-primary btn-sm" data-accept-offer="' + offer.id + '">پذیرش</button>';
            }
            row.innerHTML = html;
            list.appendChild(row);
        });
        bindAccept();
    }

    function bindAccept() {
        document.querySelectorAll('[data-accept-offer]').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                if (!confirm('این پیشنهاد پذیرفته شود؟')) return;
                btn.disabled = true;
                try {
                    const data = await api('/' + encodeURIComponent(code) + '/offers/' + btn.dataset.acceptOffer + '/accept', { method: 'POST', body: '{}' });
                    root.dataset.status = data.booking.status;
                    document.getElementById('successStatus').textContent = data.booking.status;
                    document.getElementById('successProvider').textContent = (data.booking.provider && data.booking.provider.display_name) || '—';
                    document.getElementById('successTotal').textContent = Number(data.booking.total || 0).toLocaleString('fa-IR') + ' تومان';
                    renderOffers(data.booking.offers || []);
                } catch (e) {
                    alert(e.message);
                    btn.disabled = false;
                }
            });
        });
    }

    const refresh = document.getElementById('refreshOffers');
    if (refresh) {
        refresh.addEventListener('click', async function () {
            refresh.disabled = true;
            try {
                const data = await api('/' + encodeURIComponent(code) + '/offers');
                renderOffers(data.results || []);
            } catch (e) {
                alert(e.message);
            }
            refresh.disabled = false;
        });
    }

    const cancelBtn = document.getElementById('cancelBooking');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', async function () {
            if (!confirm('رزرو لغو شود؟')) return;
            cancelBtn.disabled = true;
            try {
                const data = await api('/' + encodeURIComponent(code) + '/cancel', { method: 'POST', body: '{}' });
                document.getElementById('successStatus').textContent = data.booking.status;
                root.dataset.status = data.booking.status;
                cancelBtn.remove();
                renderOffers(data.booking.offers || []);
            } catch (e) {
                alert(e.message);
                cancelBtn.disabled = false;
            }
        });
    }

    bindAccept();
});
</script>
@endpush
