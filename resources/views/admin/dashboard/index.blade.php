@extends('admin.layout')

@section('title', 'داشبورد')

@section('content')
@include('admin.components.page-header', [
    'title' => 'داشبورد مدیریت',
    'subtitle' => 'تحلیل فروش، رزرو، کاربران، سگ‌ها و پشتیبانی.',
])

<div class="row gy-3 mb-4">
    @foreach([
        ['label' => 'کاربران', 'value' => number_format((int) ($stats['users'] ?? 0)), 'href' => route('admin.users.index'), 'icon' => 'fa-users'],
        ['label' => 'کاربر جدید ۳۰روز', 'value' => number_format((int) ($stats['new_users_30'] ?? 0)), 'href' => route('admin.users.index'), 'icon' => 'fa-user-plus'],
        ['label' => 'درآمد ۳۰روز', 'value' => number_format((int) ($stats['revenue_30'] ?? 0)), 'href' => route('admin.transactions.index'), 'icon' => 'fa-coins'],
        ['label' => 'تیکت باز', 'value' => number_format((int) ($stats['open_tickets'] ?? 0)), 'href' => route('admin.support-tickets.index', ['status' => 'open']), 'icon' => 'fa-headset'],
        ['label' => 'رزرو در صف', 'value' => number_format((int) ($stats['pending_bookings'] ?? 0)), 'href' => route('admin.bookings.index'), 'icon' => 'fa-calendar'],
        ['label' => 'سگ قابل عرضه', 'value' => number_format((int) ($stats['dogs_available'] ?? 0)), 'href' => route('admin.dogs.index'), 'icon' => 'fa-dog'],
    ] as $card)
        <div class="col-6 col-md-4 col-xl-2">
            <a class="sp-admin-stat" href="{{ $card['href'] }}">
                <span class="sp-admin-stat__icon"><i class="fas {{ $card['icon'] }}"></i></span>
                <strong class="num">{{ $card['value'] }}</strong>
                <span>{{ $card['label'] }}</span>
            </a>
        </div>
    @endforeach
</div>

@if(!empty($insights))
<div class="row gy-3 mb-4">
    @foreach($insights as $insight)
        <div class="col-md-6 col-xl-3">
            <div class="card sp-admin-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1">{{ $insight['title'] }}</div>
                    <div class="h5 mb-1 num">{{ $insight['value'] }}</div>
                    <div class="small text-muted">{{ $insight['note'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

<div class="sp-admin-shortcuts mb-4">
    <a class="btn btn-primary btn-sm" href="{{ route('admin.support-tickets.index') }}">پاسخ تیکت‌ها</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.support-messages.index') }}">صندوق پیام</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.transactions.index') }}">تراکنش‌ها</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.bookings.index') }}">رزروها</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.dogs.create') }}">سگ جدید</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.courses.create') }}">دوره جدید</a>
</div>

<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">روند فروش ۶ ماه اخیر</h5>
                <div class="sp-admin-chart" style="position:relative;height:300px;width:100%">
                    <canvas id="salesChart" aria-label="نمودار فروش"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">فروش ۱۴ روز اخیر</h5>
                <div class="sp-admin-chart" style="position:relative;height:300px;width:100%">
                    <canvas id="dailySalesChart" aria-label="فروش روزانه"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-1">
    <div class="col-md-6 col-xl-3">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">وضعیت رزروها</h5>
                <div class="sp-admin-chart" style="position:relative;height:240px">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">وضعیت سگ‌ها</h5>
                <div class="sp-admin-chart" style="position:relative;height:240px">
                    <canvas id="dogStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">وضعیت تیکت‌ها</h5>
                <div class="sp-admin-chart" style="position:relative;height:240px">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">ترکیب خدمات رزرو</h5>
                <div class="sp-admin-chart" style="position:relative;height:240px">
                    <canvas id="serviceMixChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-1">
    <div class="col-lg-6">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">کاربران جدید ۶ ماه</h5>
                <div class="sp-admin-chart" style="position:relative;height:260px">
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">روند رزرو ۶ ماه</h5>
                <div class="sp-admin-chart" style="position:relative;height:260px">
                    <canvas id="bookingsTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4 mt-1">
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
                                <td><span class="badge badge-{{ in_array($trx->status, ['completed','paid','success'], true) ? 'success' : 'secondary' }}">{{ $trx->status }}</span></td>
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
    <div class="col-lg-3">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">تیکت‌های اخیر</h5>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.support-tickets.index') }}">میز تیکت</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover sp-admin-table">
                        <thead><tr><th>کد</th><th>وضعیت</th><th></th></tr></thead>
                        <tbody>
                        @forelse($recentTickets ?? [] as $ticket)
                            @php
                                $st = (string) $ticket->status;
                                $isOpen = $st === 'open';
                                $statusLabel = ['open' => 'باز', 'answered' => 'پاسخ داده شد', 'closed' => 'بسته'][$st] ?? $st;
                            @endphp
                            <tr class="{{ $isOpen ? 'sp-admin-row-open' : '' }}">
                                <td class="num">{{ $ticket->code }}</td>
                                <td><span class="sp-admin-status {{ $isOpen ? 'is-open' : '' }}">{{ $statusLabel }}</span></td>
                                <td><a class="btn btn-xs btn-primary" href="{{ route('admin.support-tickets.show', $ticket) }}">چت</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">تیکتی نیست.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card sp-admin-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">پیام‌های تازه</h5>
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.support-messages.index') }}">صندوق</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover sp-admin-table">
                        <thead><tr><th>نام</th><th>خلاصه</th></tr></thead>
                        <tbody>
                        @forelse($recentMessages ?? [] as $msg)
                            <tr>
                                <td>{{ $msg->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($msg->message, 48) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">پیامی نیست.</td></tr>
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
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script>
(function () {
  if (typeof Chart === 'undefined') return;

  const charts = @json($charts ?? ['sales' => $chart ?? ['labels' => [], 'data' => []]]);
  const tick = '#a7b8ae';
  const grid = 'rgba(232,240,235,.08)';
  const palette = ['#3fbf74', '#d4b56a', '#6aa8ff', '#f07167', '#9b7bff', '#45c4c4', '#e8a87c', '#88b04b'];

  const money = (v) => new Intl.NumberFormat('fa-IR').format(v || 0) + ' ریال';
  const compact = (v) => new Intl.NumberFormat('fa-IR', { notation: 'compact' }).format(v || 0);

  function lineOrBar(id, type, payload, opts = {}) {
    const el = document.getElementById(id);
    if (!el || !payload) return;
    const data = (payload.data || []).map(Number);
    new Chart(el, {
      type,
      data: {
        labels: payload.labels || [],
        datasets: [{
          label: opts.label || '',
          data,
          borderWidth: type === 'line' ? 3 : 1,
          tension: 0.35,
          fill: !!opts.fill,
          borderColor: opts.color || '#3fbf74',
          backgroundColor: opts.fill ? 'rgba(63,191,116,.18)' : (opts.color || '#3fbf74'),
          pointBackgroundColor: opts.color || '#3fbf74',
          pointRadius: type === 'line' ? 3 : 0,
          borderRadius: type === 'bar' ? 6 : 0,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => opts.money
                ? money(ctx.parsed.y ?? ctx.parsed)
                : new Intl.NumberFormat('fa-IR').format(ctx.parsed.y ?? ctx.parsed ?? 0)
            }
          }
        },
        scales: opts.noScale ? {} : {
          y: {
            beginAtZero: true,
            ticks: { color: tick, callback: (v) => opts.money ? compact(v) : compact(v) },
            grid: { color: grid }
          },
          x: { ticks: { color: tick, maxRotation: 0 }, grid: { display: false } }
        }
      }
    });
  }

  function doughnut(id, payload) {
    const el = document.getElementById(id);
    if (!el || !payload) return;
    const data = (payload.data || []).map(Number);
    if (!data.length) return;
    new Chart(el, {
      type: 'doughnut',
      data: {
        labels: payload.labels || [],
        datasets: [{
          data,
          backgroundColor: palette.slice(0, data.length),
          borderWidth: 0,
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: tick, boxWidth: 10, font: { size: 11 } }
          },
          tooltip: {
            callbacks: {
              label: (ctx) => `${ctx.label}: ${new Intl.NumberFormat('fa-IR').format(ctx.parsed || 0)}`
            }
          }
        }
      }
    });
  }

  lineOrBar('salesChart', 'line', charts.sales, { label: 'فروش', fill: true, money: true });
  lineOrBar('dailySalesChart', 'bar', charts.dailySales, { label: 'فروش روزانه', money: true, color: '#6aa8ff' });
  lineOrBar('usersChart', 'line', charts.users, { label: 'کاربران', fill: true, color: '#d4b56a' });
  lineOrBar('bookingsTrendChart', 'bar', charts.bookingsTrend, { label: 'رزرو', color: '#9b7bff' });
  doughnut('bookingStatusChart', charts.bookingStatus);
  doughnut('dogStatusChart', charts.dogStatus);
  doughnut('ticketStatusChart', charts.ticketStatus);
  doughnut('serviceMixChart', charts.serviceMix);
})();
</script>
@endpush