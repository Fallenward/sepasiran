@extends('layouts.app')

@section('title', 'بیمه حیوانات')

@section('content')
    <!-- Header -->
    <x-page-header
        title="بیمه حیوانات"
        :breadcrumbs="[
            ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
            ['label' => 'بیمه حیوانات', 'route' => route('frontend.clinic.insurance.index')]
        ]"
        bgColor="#0d47a1"
        extraClasses="text-align-center"
    />

    <div class="container">
        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-4 col-12 mb-4">
                <x-filter-form :filters="$filters "/>
            </div>

            <!-- Insurance Cards -->
            <div class="col-md-8 col-12">
                <div id="items-list" class="row g-4">
                    @foreach($insurances as $insurance)
                        <div class="col-md-4 col-6">
                            <x-dog-card :item="$insurance" routeName="frontend.clinic.insurance.index" />
                        </div>
                    @endforeach
                </div>

                <!-- Load More Button -->
                @if($insurances->hasMorePages())
                    <div class="text-center mt-4">
                        <button id="load-more"
                                data-next-page="{{ $insurances->currentPage() + 1 }}"
                                class="btn btn-primary">
                            مشاهده بیشتر
                        </button>
                    </div>
                @endif

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $insurances->appends(request()->only(['city', 'duration']))->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Optional: Load More Modal (if needed) -->
    {{-- You can skip this if not using modal-based loading --}}
@endsection

<!-- Scripts -->
@push('scripts')
    <x-scripts.filter-submit />
    <x-scripts.load-more-dogs />
@endpush
