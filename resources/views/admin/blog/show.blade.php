@extends('admin.layout')

@section('template_title')
    {{ $blog->title ?? "نمایش وبلاگ" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded-lg">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">جزئیات وبلاگ</h5>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i> بازگشت
                        </a>
                    </div>

                    <div class="card-body bg-light">
                        <div class="row">
                            <!-- عنوان -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">عنوان:</strong>
                                    <span class="text-dark">{{ $blog->title }}</span>
                                </div>
                            </div>

                            <!-- اسلاگ -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">اسلاگ:</strong>
                                    <span class="text-dark">{{ $blog->slug }}</span>
                                </div>
                            </div>

                            <!-- دسته‌بندی -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">دسته‌بندی:</strong>
                                    <span class="text-dark">{{ $blog->category->name }}</span>
                                </div>
                            </div>

                            <!-- نویسنده -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">نویسنده:</strong>
                                    <span class="text-dark">{{ $blog->author->name }}</span>
                                </div>
                            </div>

                            <!-- توضیحات -->
                            <div class="col-md-12">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">توضیحات:</strong>
                                    <span class="text-dark">{{ $blog->description }}</span>
                                </div>
                            </div>

                            <!-- محتوا -->
                            <div class="col-md-12">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">محتوا:</strong>
                                    <p class="text-dark">{!! nl2br(e($blog->content)) !!}</p>
                                </div>
                            </div>

                            <!-- تصویر -->
                            <div class="col-md-12 text-center mt-3">
                                <strong class="d-block text-muted">تصویر:</strong>
                                @if ($blog->image)
                                    <img src="{{ asset('storage/' . $blog->image) }}"
                                         alt="تصویر وبلاگ" class="img-thumbnail shadow-lg"
                                         style="max-width: 400px;">
                                @else
                                    <span>بدون تصویر</span>
                                @endif
                            </div>

                            <!-- وضعیت -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">وضعیت:</strong>
                                    @if($blog->status === 'draft')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-pencil-alt"></i> پیش‌نویس
                                        </span>
                                    @elseif($blog->status === 'published')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> منتشر شده
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- تعداد بازدیدها -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">تعداد بازدید:</strong>
                                    <span class="text-dark">{{ $blog->views }}</span>
                                </div>
                            </div>

                            <!-- تعداد لایک‌ها -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">تعداد لایک‌ها:</strong>
                                    <span class="text-dark">{{ $blog->likes }}</span>
                                </div>
                            </div>

                            <!-- امتیاز -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">امتیاز:</strong>
                                    <span class="text-dark">{{ $blog->rating }}</span>
                                </div>
                            </div>

                            <!-- فعال‌سازی نظرات -->
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm">
                                    <strong class="d-block text-muted">نظرات فعال است؟</strong>
                                    <span class="text-dark">
                                        {{ $blog->comments_enabled ? 'بله' : 'خیر' }}
                                    </span>
                                </div>
                            </div>

                            <!-- دکمه بازگشت -->
                            <div class="col-md-12 text-center mt-4">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> بازگشت
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
