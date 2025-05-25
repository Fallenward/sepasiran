@extends('layouts.app')

@section('content')
<!-- Main Product Card -->
<div class="container my-5">
    <div class="row g-4 align-items-center flex-md-row-reverse">
        <!-- Product Image -->
        <div class="col-md-4 text-center">
            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/dogPicture.jpg') }}"
                 alt="{{ $product->name }}"
                 class="img-fluid rounded shadow-sm" />
        </div>

        <!-- Product Content -->
        <div class="col-md-8">
            <h3 class="fw-bold">{{ $product->name }}</h3>
            <div style="width: 60px; height: 4px; background-color: #0d47a1;" class="mb-3 rounded"></div>

            @if($product->description)
                <p class="mt-3 text-muted">{{ $product->description }}</p>
            @endif

            <ul class="list-unstyled mt-4">
                <li><strong>قیمت:</strong> {{ number_format($product->price) }} تومان</li>
                <li><strong>دسته‌بندی:</strong> {{ $product->category->name ?? 'بدون دسته‌بندی' }}</li>
                <li><strong>وضعیت:</strong> {{ $product->in_stock ? 'موجود' : 'ناموجود' }}</li>
            </ul>

            <a href="{{ route('frontend.stores.index') }}" class="btn btn-outline-secondary mt-3">
                بازگشت به لیست محصولات
            </a>
        </div>
    </div>
</div>

<!-- Recent Products Section (Static) -->
<div class="container my-5">
    <h4 class="fw-bold mb-4"> محصولات مرتبط</h4>
    <div class="row g-4">
        @foreach(range(1, 4) as $index)
            <div class="col-6 col-md-3">
                <div class="card h-100 text-center">
                    <img src="{{ asset('images/dogPicture.jpg') }}"
                         class="card-img-top" alt="محصول نمونه {{ $index }}">
                    <div class="card-body">
                        <h5 class="card-title">محصول شماره {{ $index }}</h5>
                        <p class="card-text text-muted">{{ number_format(150000 * $index) }} تومان</p>
                        <a href="#" class="btn btn-sm btn-primary">مشاهده</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Comments Section -->
<div class="container my-5">
    <h4 class="fw-bold mb-4">نظرات کاربران</h4>
    <ul class="list-unstyled">
        @foreach($comments as $comment)
            <li class="mb-4 d-flex align-items-start">
                <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                     alt="{{ $comment->user->name ?? 'کاربر' }}"
                     class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                <div>
                    <h6 class="mb-1 fw-bold">{{ $comment->user->name ?? 'کاربر' }}</h6>
                    <!-- Star rating -->
                    <div class="text-warning mb-2">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa fa-star"></i>
                        @endfor
                    </div>
                    <p>{{ $comment->content ?? '' }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection
