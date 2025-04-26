@extends('admin.layout')

@section('template_title', 'مدیریت وبلاگ‌ها')

@section('content')
    <div class="container-fluid">

        <!-- پیام موفقیت -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success" id="success-alert">{{ $message }}</div>
        @endif

        <!-- کارت -->
        <div class="card">
            <div class="card-header d-flex justify-content-between flex-wrap">
                <h5 class="mb-0">{{ __('وبلاگ‌ها') }}</h5>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">ایجاد جدید</a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>دسته‌بندی</th>
                            <th>نویسنده</th>
                            <th>امتیاز</th>
                            <th>بازدید</th>
                            <th>وضعیت</th>
                            <th>کامنت</th>
                            <th>تاریخ ایجاد</th>
                            <th width="110">عملیات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($blogs as $blog)
                            <tr>
                                <td>{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>

                                <td class="text-truncate" style="max-width:180px;">
                                    <a href="{{ route('admin.blogs.show', $blog) }}">
                                        {{ $blog->title }}
                                    </a>
                                </td>

                                <td>{{ $blog->category?->name ?? '—' }}</td>
                                <td>{{ $blog->author?->name ?? '—' }}</td>
                                <td>{{ number_format($blog->rating, 1) }}</td>
                                <td>{{ number_format($blog->views) }}</td>

                                <td>
                                    <span class="badge bg-{{ $blog->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ __($blog->status) }}
                                    </span>
                                </td>

                                <td>
                                    {!! $blog->comments_enabled
                                            ? '<i class=\"fas fa-check text-success\"></i>'
                                            : '<i class=\"fas fa-times text-danger\"></i>' !!}
                                </td>

                                <td>{{ $blog->created_at->format('Y-m-d') }}</td>

                                <!-- دکمه‌های عملیات -->
                                <td>
                                    <a href="{{ route('admin.blogs.edit', $blog) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.blogs.destroy', $blog) }}"
                                          method="POST" class="d-inline-block"
                                          onsubmit="return confirm('حذف شود؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10">هیچ وبلاگی یافت نشد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- صفحه‌بندی -->
            <div class="card-footer py-2">
                {{ $blogs->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
