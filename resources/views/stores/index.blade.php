@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')

    <x-page-header
        title="فروشگاه"
        :breadcrumbs="[
            ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
            ['label' => 'فروشگاه', 'route' => route('frontend.stores.index')],
        ]"
        bgColor="#0d47a1"
    />

    <div class="container">
        <div class="row">
            <!-- فیلترها -->
            <div class="col-lg-4 col-md-5 col-12 mb-4">
                <x-filter-form :filters="$filters" />
            </div>

            <!-- آیتم‌ها -->
            <div class="col-md-8">
                <div class="row g-4">
                    @foreach($dogs as $dog)
                        <div class="col-md-4">
                            <x-dog-card :item="$dog" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <x-scripts.filter-submit />
@endpush
