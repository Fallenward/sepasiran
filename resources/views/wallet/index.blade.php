@extends('admin.layout')
@section('title', 'کیف پول پرورش‌دهنده')

@section('content')
<div class="container-fluid pt-4 pb-5" style="background-color: #f9f9f9;">

    <!-- Wallet Balance -->
    <div class="card text-white mb-5" style="background-color: #0D47A1;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">موجودی کیف پول</h5>
            <h4 class="mb-0">{{ number_format($walletBalance) }} تومان</h4>
            <button class="btn btn-light">افزایش موجودی</button>
        </div>
    </div>

    <!-- Filters -->
    <form class="row g-3 mb-5">
        <div class="col-12 col-md-3">
            <input type="text" class="form-control" placeholder="تاریخ تراکنش">
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select">
                <option>نوع تراکنش</option>
                <option>ویزیت حیوان</option>
                <option>واکسیاسیون</option>
                <option>خرید دارو</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select class="form-select">
                <option>وضعیت تراکنش</option>
                <option>موفق</option>
                <option>ناموفق</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <button class="btn text-white w-100" style="background-color: #0D47A1;">نمایش نتایج</button>
        </div>
    </form>

    <!-- Transactions Table -->
    <div class="mb-5 p-4 border rounded" style="background-color: #fff;">
        <div class="px-3 py-2 rounded-top" style="background-color: #0d47a1; color: #fff;">
            <h5 class="mb-0">تراکنش‌های اخیر</h5>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>تاریخ تراکنش</th>
                        <th>نوع تراکنش</th>
                        <th>مبلغ</th>
                        <th>وضعیت تراکنش</th>
                        <th>جزئیات</th>
                        <th>واریزی / برداشت</th>
                    </tr>
                </thead>
                <tbody>
                    @php $visible = 5; @endphp

                    {{-- Show first 5 transactions --}}
                    @foreach ($transactions->take($visible) as $transaction)
                        <tr>
                            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d') }}</td>
                            <td>{{ $transaction->description ?? '---' }}</td>
                            <td>{{ number_format($transaction->amount) }} تومان</td>
                            <td class="{{ $transaction->status === 'completed' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->status === 'completed' ? 'موفق' : 'ناموفق' }}
                            </td>
                            <td><a href="#">مشاهده</a></td>
                            <td>{{ $transaction->amount >= 0 ? 'واریزی' : 'برداشت' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Show "Show More" button & collapsible section if more than 5 --}}
        @if ($transactions->count() > $visible)
            <div class="text-center my-3">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#moreTransactions" aria-expanded="false" aria-controls="moreTransactions">
                    نمایش تراکنش‌های بیشتر
                </button>
            </div>

            <div class="collapse" id="moreTransactions">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <tbody>
                            @foreach ($transactions->skip($visible) as $transaction)
                                <tr>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d') }}</td>
                                    <td>{{ $transaction->description ?? '---' }}</td>
                                    <td>{{ number_format($transaction->amount) }} تومان</td>
                                    <td class="{{ $transaction->status === 'completed' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->status === 'completed' ? 'موفق' : 'ناموفق' }}
                                    </td>
                                    <td><a href="#">مشاهده</a></td>
                                    <td>{{ $transaction->amount >= 0 ? 'واریزی' : 'برداشت' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="row text-center mb-5">
        <div class="col-6 col-md-3 mb-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="summary-icon"><i class="bi bi-graph-up"></i></div>
                    <div class="summary-label">سود خالص این ماه</div>
                    <div class="summary-value">{{ number_format($netProfit) }} تومان</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="summary-icon"><i class="bi bi-wallet2"></i></div>
                    <div class="summary-label">موجودی کیف پول کلینیک</div>
                    <div class="summary-value">{{ number_format($walletBalance) }} تومان</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="summary-icon"><i class="bi bi-cash-stack"></i></div>
                    <div class="summary-label">هزینه‌های این ماه</div>
                    <div class="summary-value">{{ number_format($monthlyExpense) }} تومان</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card h-100 border-0">
                <div class="card-body">
                    <div class="summary-icon"><i class="bi bi-currency-dollar"></i></div>
                    <div class="summary-label">درآمد این ماه</div>
                    <div class="summary-value">{{ number_format($monthlyIncome) }} تومان</div>
                </div>
            </div>
        </div>
    </div>



    <!-- Income Chart -->
    <div class="card mb-5">
        <div class="card-header">نمودار درآمد ماهانه</div>
        <div class="card-body">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>



</div>

<!-- Chart.js -->
<script src="{{ asset('assets/js/chart.js') }}"></script>
<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');

    const incomeData = @json($monthlyIncomeChart);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'],
            datasets: [{
                label: 'درآمد ماهانه',
                data: incomeData,
                borderColor: '#0D47A1',
                backgroundColor: 'rgba(13, 71, 161, 0.1)',
                tension: 0.4,
                fill: true
            }]
        }
    });
</script>
@endsection
