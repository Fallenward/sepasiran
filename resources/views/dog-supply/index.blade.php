@extends('layouts.app')

@section('title', 'تهیه سگ')

@section('content')
<!-- بخش عنوان و نوار مسیریابی -->
<x-page-header
    title="تهیه سگ"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'تهیه سگ', 'route' => route('frontend.dog.supply.index')]
    ]"
    extraClasses="text-align-center"
    bgColor="#0d47a1"
/>


<div class="container">
    <div class="row">
        <!-- بخش فیلتر -->
        <div class="col-md-4 col-12">
            <x-filter-form :filters="$filters" />
        </div>

        <!-- نمایش سگ‌ها -->
        <div class="col-md-8 col-12">
            <div class="row g-4" id="dogs-list">
                @foreach($dogs as $dog)
                    <div class="col-md-4 col-12">
                        <x-dog-card :item="$dog" />
                    </div>
                @endforeach
            </div>

            <!-- دکمه مشاهده بیشتر -->
            <x-load-more-button :hasMore="$dogs->hasMorePages()" :nextPage="$dogs->currentPage() + 1" />
        </div>

    </div>
</div>

<!-- Scripts -->
<x-scripts.filter-submit />


<x-scripts.load-more-dogs />

@endsection
