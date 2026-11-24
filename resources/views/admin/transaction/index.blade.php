@extends('admin.layout')

@section('title', 'تراکنش‌ها')

@section('content')
@include('admin.components.page-header', [
    'title' => 'تراکنش‌ها',
    'subtitle' => 'پرداخت‌ها، مبلغ و وضعیت درگاه.',
])

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card sp-admin-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>کاربر</th>
                    <th>کد تراکنش</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>روش</th>
                    <th>مرجع</th>
                    <th>تاریخ</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                    @php
                        $st = (string) $transaction->status;
                        $ok = in_array($st, ['completed','paid','success'], true);
                    @endphp
                    <tr>
                        <td class="num">{{ $transaction->id }}</td>
                        <td>
                            {{ optional($transaction->user)->name ?? ('#'.$transaction->user_id) }}
                            <div class="text-muted small">{{ optional($transaction->user)->email }}</div>
                        </td>
                        <td class="num">{{ $transaction->transaction_id ?: '—' }}</td>
                        <td class="num"><strong>{{ number_format((float) $transaction->amount) }}</strong></td>
                        <td><span class="sp-admin-status {{ $ok ? 'is-ok' : 'is-warn' }}">{{ $st ?: '—' }}</span></td>
                        <td>{{ $transaction->payment_method ?: '—' }}</td>
                        <td class="num">{{ $transaction->ref_id ?: '—' }}</td>
                        <td class="num">{{ optional($transaction->created_at)->timezone(config('app.timezone'))->format('Y/m/d H:i') }}</td>
                        <td class="sp-admin-actions">
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.transactions.show', $transaction) }}">نمایش</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.transactions.edit', $transaction) }}">ویرایش</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">تراکنشی نیست.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $transactions->withQueryString()->links() }}</div>
    </div>
</div>
@endsection