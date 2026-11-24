@extends('admin.layout')

@section('title', 'تراکنش #'.($transaction->id ?? ''))

@section('content')
@include('admin.components.page-header', [
    'title' => 'جزئیات تراکنش',
    'subtitle' => '#'.($transaction->id ?? '—'),
])

<div class="mb-3" style="display:flex;gap:8px;flex-wrap:wrap">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.transactions.index') }}">بازگشت</a>
    <a class="btn btn-primary btn-sm" href="{{ route('admin.transactions.edit', $transaction) }}">ویرایش</a>
</div>

<div class="card sp-admin-card">
    <div class="card-body">
        <dl class="sp-admin-kv">
            <dt>کاربر</dt>
            <dd>
                {{ optional($transaction->user)->name ?? ('#'.$transaction->user_id) }}
                <div class="text-muted small">{{ optional($transaction->user)->email }} · <span class="num">{{ optional($transaction->user)->phone }}</span></div>
            </dd>
            <dt>کد تراکنش</dt><dd class="num">{{ $transaction->transaction_id ?: '—' }}</dd>
            <dt>مرجع درگاه</dt><dd class="num">{{ $transaction->ref_id ?: '—' }}</dd>
            <dt>وضعیت</dt><dd><span class="sp-admin-status">{{ $transaction->status ?: '—' }}</span></dd>
            <dt>مبلغ</dt><dd class="num"><strong>{{ number_format((float) ($transaction->amount ?? 0)) }}</strong></dd>
            <dt>مبلغ سبد</dt><dd class="num">{{ number_format((float) ($transaction->cart_price ?? 0)) }}</dd>
            <dt>مالیات</dt><dd class="num">{{ number_format((float) ($transaction->tax ?? 0)) }}</dd>
            <dt>تخفیف</dt><dd class="num">{{ number_format((float) ($transaction->discount ?? 0)) }}</dd>
            <dt>روش پرداخت</dt><dd>{{ $transaction->payment_method ?: '—' }}</dd>
            <dt>شماره کارت</dt><dd class="num">{{ $transaction->card_number ?: '—' }}</dd>
            <dt>سبدها</dt><dd class="num">{{ is_array($transaction->cart_ids) ? implode(', ', $transaction->cart_ids) : ($transaction->cart_ids ?: '—') }}</dd>
            <dt>توضیح</dt><dd>{{ $transaction->description ?: '—' }}</dd>
            <dt>تاریخ</dt><dd class="num">{{ optional($transaction->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection