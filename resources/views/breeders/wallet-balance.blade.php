@extends('admin.layout')

@section('title', 'کیف پول پرورش‌دهنده')

<style>
    .shortcut-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f0f0f0;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        margin: 0 auto;
    }
    .shortcut-label {
        font-size: 0.9rem;
    }
    .summary-icon {
        background-color: #0D47A1;
        color: #fff;
        padding: 20px;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-size: 1.8rem;
    }
    .summary-label {
        margin-top: 10px;
        font-weight: 500;
    }
    .summary-value {
        margin-top: 5px;
        font-size: 1.1rem;
    }
</style>

@section('content')
<div class="d-flex flex-column min-vh-100" style="position: relative;">
    <div class="flex-grow-1 container-fluid pt-4 pb-5" style="background-color: #f9f9f9;">

        <!-- Wallet Balance -->
        <div class="card text-white mb-4" style="background-color: #0D47A1;">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0">موجودی کیف پول</h5>
                <h4 class="mb-0">{{ number_format($walletBalance) }} تومان</h4>
                <button class="btn btn-light">افزایش موجودی</button>
            </div>
        </div>

        <!-- Filters -->
        <form class="row g-2 mb-4">
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
                <h5 class="mb-0">تراکنش‌های پرورش‌دهنده</h5>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered mb-0">
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
                        @forelse($transactions as $transaction)
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">هیچ تراکنشی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row text-center mb-5">
            <div class="col-6 col-md-3 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="summary-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="summary-label">میزان سود خالص این ماه</div>
                        <div class="summary-value">{{ number_format($netProfit) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="summary-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="summary-label">موجودی کیف پول کلینیک</div>
                        <div class="summary-value">{{ number_format($walletBalance) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="summary-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="summary-label">کل هزینه‌های این ماه</div>
                        <div class="summary-value">{{ number_format($monthlyExpense) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card h-100 border-0">
                    <div class="card-body">
                        <div class="summary-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="summary-label">کل درآمد این ماه</div>
                        <div class="summary-value">{{ number_format($monthlyIncome) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Chart -->
        <div class="card mb-4">
            <div class="card-header">نمودار درآمد</div>
            <div class="card-body">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
    <nav class="navbar fixed-bottom navbar-light bg-light d-flex justify-content-around border-top bottom-nav">
        <a class="nav-link text-center" href="#"><i class="bi bi-house"></i><br>خانه</a>
        <a class="nav-link text-center" href="#"><i class="bi bi-receipt"></i><br>سفارشات</a>
        <a class="nav-link text-center" href="#"><i class="bi bi-plus-circle"></i><br>افزودن</a>
        <a class="nav-link text-center" href="#"><i class="bi bi-gear"></i><br>مدیریت</a>
        <a class="nav-link text-center" href="#"><i class="bi bi-person"></i><br>پروفایل</a>
    </nav>
</div>

<!-- Chartjs -->
<script src="{{asset('assets/js/chart.js')}}"></script>

<!-- Chart.js -->
<script defer>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی'],
            datasets: [{
                label: 'درآمد ماهانه',
                data: [85, 30, 20, 60, 55, 80, 45, 95, 10, 0], // Replace with dynamic data
                borderColor: '#0D47A1',
                backgroundColor: 'rgba(13, 71, 161, 0.1)',
                tension: 0.3,
                fill: true
            }]
        }
    });
</script>
@endsection
