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
<div class="container mb-6">
    <h3 class="fw-bold mb-4">سوالات متداول</h3>
    <div class="bg-light p-4 rounded shadow-sm">
        <div class="mb-4">
            <h6 class="fw-bold">چطور می‌توانم به پناهگاه کمک کنم؟</h6>
            <p>شما می‌توانید با اهدای غذا، وسایل مورد نیاز یا حمایت مالی از پناهگاه حمایت کنید.</p>
        </div>
        <div class="mb-4">
            <h6 class="fw-bold">چه نوع حیواناتی در این پناهگاه نگهداری می‌شوند؟</h6>
            <p>ما عمدتاً از سگ‌ها و گاهی گربه‌های بی‌سرپرست مراقبت می‌کنیم.</p>
        </div>
        <div class="mb-4">
            <h6 class="fw-bold">چطور می‌توانم به عنوان داوطلب ثبت‌نام کنم؟</h6>
            <p>از طریق فرم ثبت‌نام داوطلب در وب‌سایت می‌توانید اطلاعات خود را ارسال کرده و با ما همکاری کنید.</p>
        </div>
        <div>
            <h6 class="fw-bold">آیا می‌توانم حیوانی را به سرپرستی قبول کنم؟</h6>
            <p>بله، پس از بررسی شرایط شما و حیوان، فرآیند سرپرستی انجام می‌شود.</p>
        </div>
    </div>
</div>



    <!-- Scripts -->
    <x-scripts.filter-submit />
    <x-scripts.load-more-dogs />
@endsection
