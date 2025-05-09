@extends('layouts.app')

@section('title', 'پرورش دهنده')

@section('content')
    <!-- Banner -->
    <div class="mb-5 p-5 text-center text-white rounded" style="background-color: #0D47A1;">
        <h1 class="fw-bold mb-2">پرورش دهنده</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.home') }}" class="text-white text-decoration-none">سپاس ایران</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.breeders.index') }}" class="text-white text-decoration-none">پرورش دهنده</a>
                </li>
            </ol>
        </nav>
    </div>

    <div class="container">
        <div class="row">
            <!-- Sidebar: Filters -->
            <aside class="col-lg-4 col-md-5 col-12 mb-4">
                <div class="collapse d-md-block" id="filterPanel">
                    <div class="p-3 bg-light rounded w-100" style="direction: rtl; max-width: 350px;">
                        <h5 class="fw-bold">فیلترها</h5>

                        <!-- City Filter -->
                        <div class="mb-4 border rounded p-2">
                            <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">شهر</h6>
                            @foreach($cities as $city)
                                <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label me-2" for="city_{{ $city->id }}">{{ $city->name }}</label>
                                    <input class="form-check-input" type="checkbox" id="city_{{ $city->id }}" name="city[]" value="{{ $city->id }}" {{ in_array($city->id, request('city', [])) ? 'checked' : '' }}>
                                </div>
                            @endforeach
                        </div>

                        <!-- Breed Filter -->
                        <div class="mb-4 border rounded p-2">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="background-color: #0d47a1; padding: 5px 10px; border-radius: 5px;">
                                <h6 class="text-white mb-0">نژاد</h6>
                                <button class="btn btn-sm text-white px-3 py-1" style="background-color: #0d47a1;" type="button" data-bs-toggle="collapse" data-bs-target="#allBreeds" aria-expanded="false" aria-controls="allBreeds">
                                    بیشتر
                                </button>
                            </div>
                            <div class="bg-light p-2 rounded" style="background-color: #0d47a1;">
                                @foreach($breeds->take(4) as $breed)
                                    <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-check-label me-2 text-white" for="breed_{{ $breed->id }}">{{ $breed->name }}</label>
                                        <input class="form-check-input" type="checkbox" id="breed_{{ $breed->id }}" name="breed[]" value="{{ $breed->id }}" {{ in_array($breed->id, request('breed', [])) ? 'checked' : '' }}>
                                    </div>
                                @endforeach
                            </div>
                            <div class="collapse mt-2" id="allBreeds">
                                <div class="bg-light p-2 rounded" style="background-color: #0d47a1;">
                                    @foreach($breeds->slice(4) as $breed)
                                        <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-check-label me-2 text-white" for="breed_{{ $breed->id }}">{{ $breed->name }}</label>
                                            <input class="form-check-input" type="checkbox" id="breed_{{ $breed->id }}" name="breed[]" value="{{ $breed->id }}" {{ in_array($breed->id, request('breed', [])) ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Size Filter -->
                        <div class="mb-4 border rounded p-2">
                            <h6 class="text-white px-2 py-1 rounded" style="background-color: #0d47a1;">اندازه سگ</h6>
                            @foreach(['mini' => 'کوچک (Mini)', 'medium' => 'متوسط (Medium)', 'large' => 'بزرگ (Large)'] as $key => $label)
                                <div class="form-check d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-check-label me-2" for="size_{{ $key }}">{{ $label }}</label>
                                    <input class="form-check-input" type="checkbox" id="size_{{ $key }}" name="size[]" value="{{ $key }}" {{ in_array($key, request('size', [])) ? 'checked' : '' }}>
                                </div>
                            @endforeach
                        </div>

                        <form method="GET" action="{{ route('frontend.breeders.index') }}">
                            <button type="submit" class="btn btn-primary btn-sm w-100">اعمال فیلترها</button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main: Breeder Cards -->
            <section class="col-lg-8 col-md-7 col-12 mb-4">
                <div class="row g-4" id="breeders-list">
                    @foreach($breeders as $breeder)
                        <div class="col-6 col-md-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $breeder->image) }}" class="card-img-top" alt="{{ $breeder->name }}">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold">{{ $breeder->name }}</h6>
                                    <small>({{ $breeder->reviews_count ?? 0 }} نظر)</small>
                                    <p class="text-muted small mb-3">{{ Str::limit($breeder->description, 60) }}</p>
                                    <a href="{{ route('frontend.breeders.index', $breeder) }}" class="btn btn-outline-primary btn-sm mt-auto">مشاهده</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-4">
                    @if($breeders->hasMorePages())
                        <button id="load-more" data-next-page="{{ $breeders->currentPage() + 1 }}" class="btn btn-primary">مشاهده بیشتر</button>
                    @endif
                </div>

                <!-- Modal for more breeders -->
                <div class="modal fade" id="moreBreedersModal" tabindex="-1" aria-labelledby="moreBreedersLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="moreBreedersLabel">پرورش دهندگان بیشتر</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                            </div>
                            <div class="modal-body" id="moreBreedersContent">
                                <!-- dynamically loaded breeders -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- /.row -->
    </div><!-- /.container -->

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loadMoreBtn = document.getElementById('load-more');
            const modal = new bootstrap.Modal(document.getElementById('moreBreedersModal'));

            loadMoreBtn?.addEventListener('click', function () {
                const next = this.dataset.nextPage;
                fetch(`{{ url()->current() }}?page=${next}`)
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const cards = doc.querySelectorAll('#breeders-list .col-6');
                        const container = document.getElementById('moreBreedersContent');
                        container.innerHTML = '';
                        cards.forEach(c => container.appendChild(c.cloneNode(true)));
                        modal.show();
                    });
            });
        });
    </script>
    @endpush

@endsection
