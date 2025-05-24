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
                            <x-insurance-card :item="$insurance" routeName="frontend.clinic.insurance.index" />
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

<!-- Card Section -->
<div class="card shadow-sm p-3 mb-4 w-100" style="height: 480px;">
    <div class="d-flex flex-column flex-md-row align-items-stretch h-100">
        <!-- Text Content (Left) -->
        <div class="w-100 w-md-50 pe-md-4 d-flex flex-column justify-content-between h-100">
            <div class="card-body p-3">
                <h5 class="card-title fw-bold mb-3">عنوان کارت</h5>
                <p class="card-text mb-4" style="font-size: 1rem;">
                    توضیحاتی درباره این بخش که می‌تواند شامل جزئیات بیشتری درباره موضوع کارت باشد. این متن کمی طولانی‌تر است تا با ارتفاع تصویر هماهنگ شود.
                </p>
            </div>
            <div class="d-flex justify-content-start mb-3 px-3">
                <a href="#" class="btn btn-primary me-2">مشاوره بیمه</a>
                <a href="#" class="btn btn-outline-primary">کلینیک ها</a>
            </div>
        </div>

        <!-- Image (Right) -->
        <div class="w-100 w-md-50 d-flex justify-content-center align-items-center p-3 h-100">
            <img src="{{ asset('images/dogPicture.jpg') }}" alt="تصویر کارت" class="img-fluid rounded" style="max-width: 2000px; max-height: 100%;">
        </div>
    </div>
</div>


<!-- بخش نظرات -->
<div class="container mb-6">
    <h3 class="fw-bold mb-4">نظرات در مورد مستر روتوایلر</h3>
    <div class="row g-4">
        @foreach(range(1,2) as $i)
            <div class="col-12">
                <div class="card p-3 h-100 shadow-sm rounded">
                    <h6 class="fw-bold mb-2">حمید حمیدی</h6>
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ...</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center mt-4">
        <a href="#" class="btn btn-outline-primary">مشاهده بیشتر</a>
    </div>
</div>

<!-- بخش سوالات متداول -->

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


    <!-- Optional: Load More Modal (if needed) -->
    {{-- You can skip this if not using modal-based loading --}}
@endsection

<!-- Scripts -->
@push('scripts')
    <x-scripts.filter-submit />
    <x-scripts.load-more-dogs />
@endpush
