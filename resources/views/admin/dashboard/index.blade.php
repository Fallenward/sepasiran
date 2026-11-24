@extends('admin.layout')

@section('title', 'داشبورد')

@section('content')
@include('admin.components.page-header', [
    'title' => 'داشبورد مدیریت',
    'subtitle' => 'وضعیت زندهٔ عملیات، صف‌ها و میانبرهای پرکاربرد.',
])

<div class="row gy-3 mb-4">
    @foreach([
        ['label' => 'کاربران', 'value' => $stats['users'] ?? 0, 'href' => route('admin.users.index'), 'icon' => 'fa-users'],
        ['label' => 'تیکت باز', 'value' => $stats['open_tickets'] ?? 0, 'href' => route('admin.support-tickets.index', ['status' => 'open']), 'icon' => 'fa-headset'],
        ['label' => 'پیام تماس', 'value' => $stats['inbox'] ?? 0, 'href' => route('admin.support-messages.index'), 'icon' => 'fa-inbox'],
        ['label' => 'رزرو در صف', 'value' => $stats['pending_bookings'] ?? 0, 'href' => route('admin.bookings.index'), 'icon' => 'fa-calendar'],
        ['label' => 'سگ‌ها', 'value' => $stats['dogs'] ?? 0, 'href' => route('admin.dogs.index'), 'icon' => 'fa-dog'],
        ['label' => 'تراکنش ۳۰روز', 'value' => $stats['transactions'] ?? 0, 'href' => route('admin.transactions.index'), 'icon' => 'fa-money-bill'],
    ] as $card)
        <div class="col-6 col-md-4 col-xl-2">
            <a class="sp-admin-stat" href="{{ $card['href'] }}">
                <span class="sp-admin-stat__icon"><i class="fas {{ $card['icon'] }}"></i></span>
                <strong class="num">{{ number_format((int) $card['value']) }}</strong>
                <span>{{ $card['label'] }}</span>
            </a>
        </div>
    @endforeach
</div>

<div class="sp-admin-shortcuts mb-4">
    <a class="btn btn-primary btn-sm" href="{{ route('admin.support-tickets.index') }}">پاسخ تیکت‌ها</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.support-messages.index') }}">صندوق پیام</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dogs.create') }}">سگ جدید</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.courses.create') }}">دوره جدید</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.products.create') }}">محصول جدید</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('frontend.shelter.index') }}" target="_blank">پناهگاه سایت</a>
</div>

<div class="row gy-4">
    <div class="col-lg-7">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">روند فروش ۶ ماه اخیر</h5>
                <canvas id="salesChart" style="height:260px"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">آخرین تراکنش‌ها</h5>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-secondary">همه</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover sp-admin-table">
                        <thead>
                        <tr>
                            <th>کد</th>
                            <th>کاربر</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentTransactions ?? [] as $trx)
                            <tr>
                                <td class="num">{{ $trx->id }}</td>
                                <td>{{ optional($trx->user)->name ?? '—' }}</td>
                                <td class="num">{{ number_format((int) $trx->amount) }}</td>
                                <td><span class="badge badge-{{ $trx->status === 'completed' || $trx->status === 'paid' ? 'success' : 'secondary' }}">{{ $trx->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">تراکنشی نیست.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-1">
    <div class="col-lg-6">
        <div class="card sp-admin-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">تیکت‌های اخیر</h5>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.support-tickets.index') }}">میز تیکت</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover sp-admin-table">
                        <thead><tr><th>کد</th><th>موضوع</th><th>وضعیت</th><th></th></tr></thead>
                        <tbody>
                        @forelse($recentTickets ?? [] as $ticket)
                            <tr class="{{ $ticket->unread_for_admin ? 'table-warning' : '' }}">
                                <td class="num">{{ $ticket->code }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->status }}</td>
                                <td><a class="btn btn-xs btn-primary" href="{{ route('admin.support-tickets.show', $ticket) }}">چت</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">تیکتی نیست.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card sp-admin-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">پیام‌های تازه</h5>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.support-messages.index') }}">صندوق</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover sp-admin-table">
                        <thead><tr><th>نام</th><th>خلاصه</th><th>تاریخ</th></tr></thead>
                        <tbody>
                        @forelse($recentMessages ?? [] as $msg)
                            <tr>
                                <td>{{ $msg->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($msg->message, 60) }}</td>
                                <td class="num">{{ optional($msg->created_at)->format('Y/m/d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">پیامی نیست.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
if (document.getElementById('salesChart')) {
  new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
      labels: @json($chart['labels'] ?? []),
      datasets: [{
        data: @json($chart['data'] ?? []),
        borderWidth: 2,
        tension: .35,
        fill: false,
        borderColor: '#4fbf78',
        backgroundColor: 'rgba(79,191,120,.2)'
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { callback: v => new Intl.NumberFormat('fa-IR').format(v), color: '#8a9e93' }, grid: { color: 'rgba(232,240,235,.06)' } },
        x: { ticks: { color: '#8a9e93' }, grid: { display: false } }
      }
    }
  });
}
</script>
@endpush
