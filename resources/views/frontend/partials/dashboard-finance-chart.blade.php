@php
    $financeChart = $financeChart ?? ['labels' => [], 'inflow' => [], 'outflow' => [], 'net' => []];
    $chartId = $chartId ?? ('spFinanceChart_' . uniqid());
@endphp
<section class="sp-dash__panel sp-dash__panel--chart">
    <header class="sp-dash__panel-head">
        <h2>{{ $chartTitle ?? 'نمودار تراکنش‌های مالی' }}</h2>
        <a href="{{ $chartLinkUrl ?? route('wallet.index') }}">{{ $chartLinkLabel ?? 'کیف پول' }}</a>
    </header>
    <div class="sp-dash__chart-wrap">
        <canvas id="{{ $chartId }}" height="120" aria-label="نمودار تراکنش‌های مالی"></canvas>
    </div>
</section>

@once
    @push('scripts')
        <script src="{{ asset('assets/js/chart.js') }}"></script>
    @endpush
@endonce

@push('scripts')
<script>
(() => {
    const el = document.getElementById(@json($chartId));
    if (!el || typeof Chart === 'undefined') return;

    const labels = @json($financeChart['labels'] ?? []);
    const inflow = @json($financeChart['inflow'] ?? []);
    const outflow = @json($financeChart['outflow'] ?? []);

    new Chart(el, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'واریز',
                    data: inflow,
                    backgroundColor: 'rgba(79, 191, 120, 0.75)',
                    borderColor: '#4FBF78',
                    borderWidth: 1,
                    borderRadius: 8,
                    maxBarThickness: 28,
                },
                {
                    label: 'برداشت',
                    data: outflow,
                    backgroundColor: 'rgba(212, 181, 106, 0.55)',
                    borderColor: '#D4B56A',
                    borderWidth: 1,
                    borderRadius: 8,
                    maxBarThickness: 28,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#C5D4CB',
                        boxWidth: 12,
                        usePointStyle: true,
                        padding: 16,
                    },
                },
                tooltip: {
                    backgroundColor: '#121A17',
                    titleColor: '#E8F0EB',
                    bodyColor: '#C5D4CB',
                    borderColor: 'rgba(125, 207, 154, 0.28)',
                    borderWidth: 1,
                },
            },
            scales: {
                x: {
                    grid: { color: 'rgba(232, 240, 235, 0.06)' },
                    ticks: { color: '#8A9E93' },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(232, 240, 235, 0.06)' },
                    ticks: {
                        color: '#8A9E93',
                        callback: (v) => new Intl.NumberFormat('fa-IR').format(v),
                    },
                },
            },
        },
    });
})();
</script>
@endpush
