@extends('layouts.app')

@section('title', 'پرورش دهنده')

@section('content')
    <!-- Header -->
<x-page-header
    title="پرورش دهنده"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'پرورش دهنده', 'route' => route('frontend.breeders.index')],
    ]"
    bgColor="#0d47a1"
    extraClasses="text-align-center"
/>


<div class="wrap sp-listing" style="padding-bottom:48px">
    <div class="sp-listing__layout">
        <div class="sp-listing__filters">
            <x-filter-form :filters="$filters" :action="request()->url()" />
        </div>

        <div class="sp-listing__results">
            <div id="items-list" class="row g-4">
                @foreach($breeders as $breeder)
                    <div class="col-md-4 col-6">
                        <x-breeder-card :item="$breeder" />
                    </div>
                @endforeach
            </div>

            @if($breeders->hasMorePages())
                <div class="text-center mt-4">
                    <button id="load-more"
                            data-next-page="{{ $breeders->currentPage() + 1 }}"
                            class="btn btn-primary">
                        مشاهده بیشتر
                    </button>
                </div>
            @endif

            <div class="mt-4">
                {{ $breeders->appends(request()->only(['city', 'breed', 'size']))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <x-scripts.filter-submit />
    <x-scripts.load-more-dogs />
@endpush
