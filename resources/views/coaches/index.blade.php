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





<x-scripts.filter-submit />
@endsection
