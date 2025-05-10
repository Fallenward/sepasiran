@extends('layouts.app')

@section('title', 'کلینیک ها')

@section('content')
    <!-- Page Header -->
    <x-page-header
        title="کلینیک ها"
        :breadcrumbs="[
            ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
            ['label' => 'کلینیک ها', 'route' => route('frontend.clinics.index')]
        ]"
        extraClasses="text-align-center"
        bgColor="#0d47a1"
    />

    <div class="container">
        <div class="row">
            <!-- Filter Section -->
            <div class="col-md-4 col-12">
                <x-filter-form :filters="$filters" />
            </div>

            <!-- Clinic Cards -->
            <div id="items-list" class="row g-4">
                @foreach($clinics as $clinic)
                    <div class="col-md-4 col-12">
                        <x-dog-card :item="$clinic" />
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            @if($clinics->hasMorePages())
                <button id="load-more" data-next-page="{{ $clinics->currentPage() + 1 }}" class="btn btn-primary">
                    Load More Clinics
                </button>
            @endif

            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $clinics->appends(request()->only(['city', 'specialty']))->links() }}
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <x-scripts.filter-submit />
    <x-scripts.load-more-dogs />
@endsection
