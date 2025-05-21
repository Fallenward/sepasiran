@extends('layouts.app')

@section('title', 'فروشگاه')

@section('content')

    <x-page-header
        title="فروشگاه"
        :breadcrumbs="[
            ['label' => 'سپاس ایران', 'route' => route('frontend.home')],
            ['label' => 'فروشگاه', 'route' => route('frontend.stores.index')],
        ]"
        bgColor="linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)"
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

<!-- Blogs Section -->
<div class="mt-5">
    <h3 class="fw-bold mb-4 justify-content-center">بلاگ‌ها</h3>
    <div class="row g-4 justify-content-center">
        @foreach($blogs as $blog)
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm d-flex flex-column">
                    <img src="{{ $blog->image ? asset('storage/' . $blog->image) : asset('images/blog-placeholder.jpg') }}"
                         class="card-img-top" alt="{{ $blog->title }}" style="object-fit: cover; height: 220px;">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center px-4">
                        <h5 class="card-title">{{ Str::limit($blog->title, 50) }}</h5>
                        <p class="card-text flex-grow-1 text-muted">{{ Str::limit($blog->description ?? $blog->content ?? '', 100) }}</p>
                        <a href="#" class="btn btn-outline-primary mt-3" style="border-color: #0d47a1; color: #0d47a1;">
                            بیشتر بخوانید
                        </a>
                    </div>
                    <div style="border-bottom: 4px solid #0d47a1;"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>


@endsection

@push('scripts')
    <x-scripts.filter-submit />
@endpush
