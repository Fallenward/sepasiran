@extends('admin.layout')

@section('content')
    <div class="container" dir="rtl">
        <!-- Title -->
        <h1 class="h3 fw-bold mb-4 text-dark">داشبورد مدیریت</h1>

        <!-- Stats cards -->
        @php($items = [
            ['count'=>$stats['users']??0,'label'=>'کاربران','icon'=>'users','color'=>'primary'],
            ['count'=>$stats['transactions']??0,'label'=>'تراکنش‌ها (۳۰ روز اخیر)','icon'=>'money-bill-wave','color'=>'success'],
            ['count'=>$stats['courses']??0,'label'=>'دوره‌ها','icon'=>'book','color'=>'warning'],
            ['count'=>$stats['dogs']??0,'label'=>'سگ‌ها','icon'=>'dog','color'=>'danger'],
        ])
        <div class="row gy-4">
            @foreach($items as $it)
                <div class="col-12 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-container flex-shrink-0 d-inline-flex align-items-center justify-content-center rounded-circle bg-{{$it['color']}} bg-opacity-25" style="width:56px;height:56px;">
                                <i class="fas fa-{{$it['icon']}} text-{{$it['color']}} fs-4"></i>
                            </div>
                            <div class="me-3 pe-2 border-end" style="min-width:0;">
                                <p class="mb-1 text-muted small text-truncate">{{$it['label']}}</p>
                                <p class="mb-0 fw-semibold fs-5">{{$it['count']}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chart & Latest Transactions -->
        <div class="row mt-4 gy-4">
            <!-- Sales chart -->
            <div class="col-lg-7">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">روند فروش ۶ ماه اخیر</h5>
                        <canvas id="salesChart" style="height:260px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body table-responsive">
                        <h5 class="card-title mb-3">آخرین تراکنش‌ها</h5>
                        <table class="table table-sm table-hover align-middle text-end">
                            <thead class="table-light">
                            <tr>
                                <th>کد</th>
                                <th>مشتری</th>
                                <th>مبلغ (ریال)</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>
                            <tbody>

                            @isset($recentTransactions)
                                @forelse($recentTransactions as $trx)
                                    <tr>
                                        <td>{{$trx->id}}</td>
                                        <td class="text-truncate" style="max-width:120px;">{{ optional($trx->user)->name ?? '—' }}</td>
                                        <td>{{number_format($trx->amount)}}</td>
                                        <td>
                                            <span class="badge bg-{{ $trx->status=='paid' ? 'success' : 'danger' }} bg-opacity-25 text-{{ $trx->status=='paid' ? 'success' : 'danger' }} fw-normal">
                                                {{ __($trx->status) }}
                                            </span>
                                        </td>
                                        <td>{{$trx->created_at->format('Y-m-d')}}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">تراکنشی یافت نشد.</td></tr>
                                @endforelse
                            @else
                                <tr><td colspan="5" class="text-center text-muted">داده‌ای برای نمایش وجود ندارد.</td></tr>
                            @endisset
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
        if(document.getElementById('salesChart')){
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: @json($chart['labels'] ?? []),
                    datasets: [{
                        data: @json($chart['data'] ?? []),
                        borderWidth: 2,
                        tension:.3,
                        fill:false,
                    }]
                },
                options:{
                    plugins:{legend:{display:false}},
                    scales:{y:{ticks:{callback:v=>new Intl.NumberFormat('fa-IR').format(v)}}}
                }
            });
        }
    </script>
@endpush
