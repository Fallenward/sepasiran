@extends('layouts.app')

@section('title', 'مربیان')

@section('content')
<!-- بخش عنوان و نوار مسیریابی -->
<x-page-header
    title="مربیان"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'مربیان', 'route' => route('frontend.coaches.index')]
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

        <!-- نمایش مربیان -->
        <div class="col-md-8 col-12">
            <div class="row g-4" id="coaches-list">
                @foreach($coaches as $coach)
                    <div class="col-md-4 col-12">
                        <x-dog-card :item="$coach" />
                    </div>
                @endforeach
            </div>

            <!-- دکمه مشاهده بیشتر -->
            <x-load-more-button :hasMore="$coaches->hasMorePages()" :nextPage="$coaches->currentPage() + 1" />
        </div>
    </div>
</div>


<!-- Scripts -->
<x-scripts.filter-submit />
@endsection
