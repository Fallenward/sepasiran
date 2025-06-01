@extends('admin.layout')

@section('template_title', 'جزئیات محصول')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>محصول: {{ $product->title }}</h5>
        </div>
        <div class="card-body">
            <p><strong>نامک:</strong> {{ $product->slug }}</p>
            <p><strong>قیمت:</strong> {{ number_format($product->price) }} تومان</p>
            <p><strong>قیمت تخفیفی:</strong> {{ $product->discount_price ? number_format($product->discount_price) . ' تومان' : '—' }}</p>
            <p><strong>موجودی:</strong> {{ $product->stock }}</p>
            <p><strong>وضعیت:</strong> <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">{{ $product->is_active ? 'فعال' : 'غیرفعال' }}</span></p>
            <p><strong>دسته‌بندی:</strong> {{ $product->category?->name ?? '—' }}</p>
            <p><strong>برند:</strong> {{ $product->brand?->name ?? '—' }}</p>
            <p><strong>تاریخ ایجاد:</strong> {{ $product->created_at->format('Y-m-d') }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">ویرایش</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">بازگشت</a>
        </div>
    </div>
</div>
@endsection
