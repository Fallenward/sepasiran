@extends('layouts.app')

@section('title', 'بیمه حیوانات')

@section('content')
    <!-- Banner -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #0D47A1;">
        <h1 class="fw-bold mb-2">بیمه حیوانات</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.clinic.insurance.index') }}" class="text-white text-decoration-none">بیمه حیوانات</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <!-- Mobile Filters Toggle -->
        <div class="row d-md-none mb-3">
            <div class="col-12">
                <button class="btn btn-outline-primary w-100"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#filterPanel"
                        aria-expanded="false"
                        aria-controls="filterPanel">
                    فیلترها
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar: Filters -->
            <aside class="col-lg-4 col-md-5 col-12 mb-4">
                <div class="collapse d-md-block" id="filterPanel">
                    <div class="p-3 bg-light rounded w-100" style="direction: rtl; max-width: 350px;">
                        <h5 class="fw-bold">فیلترها</h5>

                        <!-- City Filter -->
                        <div class="mb-4 border rounded p-2">
                            <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">
                                شهر
                            </h6>
                            @foreach($cities as $city)
                                <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label me-2" for="city_{{ $city->id }}">
                                        {{ $city->name }}
                                    </label>
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="city_{{ $city->id }}"
                                           name="city[]"
                                           value="{{ $city->id }}"
                                           {{ in_array($city->id, request('city', [])) ? 'checked' : '' }}>
                                </div>
                            @endforeach
                        </div>

                        <!-- Duration Filter -->
                        <div class="mb-4 border rounded p-2">
                            <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">
                                مدت زمان بیمه
                            </h6>
                            @foreach($durations as $key => $label)
                                <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label me-2" for="duration_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="duration_{{ $key }}"
                                           name="duration[]"
                                           value="{{ $key }}"
                                           {{ in_array($key, request('duration', [])) ? 'checked' : '' }}>
                                </div>
                            @endforeach
                        </div>

                        <form method="GET" action="{{ route('frontend.clinic.insurance.index') }}">
                            <button type="submit" class="btn btn-primary btn-sm w-100">اعمال فیلترها</button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main: Insurance Cards -->
            <section class="col-lg-8 col-md-7 col-12 mb-4">
                <div class="row g-4" id="insurances-list">
                    @foreach($insurances as $insurance)
                        <div class="col-6 col-md-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $insurance->image) }}" class="card-img-top" alt="{{ $insurance->title }}">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold">{{ $insurance->title }}</h6>
                                    <p class="text-muted small mb-3">{{ Str::limit($insurance->description, 60) }}</p>
                                    <a href="{{ route('frontend.clinic.insurance.index', $insurance) }}"
                                       class="btn btn-outline-primary btn-sm mt-auto">
                                        مشاهده جزئیات
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-4">
                    @if($insurances->hasMorePages())
                        <button id="load-more" data-next-page="{{ $insurances->currentPage() + 1 }}" class="btn btn-primary">
                            مشاهده بیشتر
                        </button>
                    @endif
                </div>

                <!-- Ajax Modal -->
                <div class="modal fade" id="moreInsurancesModal" tabindex="-1" aria-labelledby="moreInsurancesLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="moreInsurancesLabel">بیمه‌های بیشتر</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                            </div>
                            <div class="modal-body" id="moreInsurancesContent">
                                <!-- Loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn   = document.getElementById('load-more');
    const modal = new bootstrap.Modal(document.getElementById('moreInsurancesModal'));
    btn?.addEventListener('click', function () {
        const next = this.dataset.nextPage;
        fetch(`?page=${next}`)
            .then(r => r.text())
            .then(html => {
                const doc   = new DOMParser().parseFromString(html, 'text/html');
                const cards = doc.querySelectorAll('#insurances-list .col-6');
                const container = document.getElementById('moreInsurancesContent');
                container.innerHTML = '';
                cards.forEach(c => container.appendChild(c.cloneNode(true)));
                modal.show();
            });
    });
});
</script>
@endpush
