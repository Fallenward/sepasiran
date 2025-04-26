@extends('admin.layout')

@section('template_title')
    مدیریت وبلاگ‌ها
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span id="card_title">{{ __('وبلاگ‌ها') }}</span>
                        <div id="loading" class="text-primary d-none">در حال بارگذاری...</div>
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">{{ __('ایجاد جدید') }}</a>
                        </div>
                    </div>

                    <!-- پیام موفقیت -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4" id="success-alert">
                            <p class="m-0">{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover text-center">
                                <thead class="thead">
                                <tr>
                                    <th>عنوان</th>
                                    <th>دسته‌بندی</th>
                                    <th>نویسنده</th>
                                    <th>تعداد نظرات</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="blogTable">
                                @include('admin.blog._table', ['blogs' => $blogs])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $blogs->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
