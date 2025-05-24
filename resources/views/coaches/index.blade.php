@extends('layouts.app')

@section('title', 'مربیان')

@section('content')
<x-page-header
    title="مربیان"
    :breadcrumbs="[
        ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
        ['label' => 'مربیان', 'route' => route('frontend.coaches.index')],
    ]"
    bgColor="linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)"
/>

<div class="container">
    <div class="row">
        <!-- Filters -->
        <div class="col-md-4 col-12">
            <x-filter-form :filters="$filters" />
        </div>

        <!-- Coaches List -->
        <div class="col-md-8 col-12">
            <div class="row g-4" id="coaches-list">
                @foreach($coaches as $coach)
                    <div class="col-md-4 col-12">
                        <x-coach-card :item="$coach" />
                    </div>
                @endforeach
            </div>

            <!-- Load More Button -->
            <x-load-more-button :hasMore="$coaches->hasMorePages()" :nextPage="$coaches->currentPage() + 1" />
        </div>
    </div>
</div>


{{-- Section: FAQ --}}
<section class="section-spacing text-center">
    <h2 class="fw-bold section-title">سوالات متداول</h2>

    <div class="bg-light rounded overflow-hidden mt-4">
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading1">
                    <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                        جلسات چقدر طول می‌کشد؟
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted text-end">
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...
                    </div>
                </div>
            </div>

            @foreach ([
                'آیا امکان آموزش در خانه من هست؟',
                'مربیان با چه نژادهایی کار می‌کنند؟',
                'لورم ایپسوم متن ساختگی؟',
                'لورم ایپسوم متن ساختگی؟'
            ] as $index => $question)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $index + 2 }}">
                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index + 2 }}">
                            {{ $question }}
                        </button>
                    </h2>
                    <div id="collapse{{ $index + 2 }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted text-end">
                            پاسخ مربوط به سوال: "{{ $question }}"
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    /* Thick blue underline for the section title */
    .section-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 2rem;
    }

    .section-title::after {
        content: "";
        display: block;
        width: 120px;
        height: 5px;
        background-color: #0d47a1;
        border-radius: 3px;
        margin: 10px auto 0;
    }

    /* Light blue background for the open/active accordion item */
    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd; /* Light blue */
        color: #000;
    }

    .accordion-button:focus {
        box-shadow: none;
    }
</style>




<x-scripts.filter-submit />
@endsection
