@extends('admin.layout')

@section('template_title', 'مدیریت محصولات مربی')

@section('content')
<div class="container-fluid">

    <!-- Success message -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success" id="success-alert">{{ $message }}</div>
    @endif

    <!-- Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
            <h5 class="mb-0">محصولات {{ $coach->name }}</h5>



        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>عنوان محصول</th>
                            <th>قیمت</th>
                            <th>قیمت با تخفیف</th>
                            <th>موجودی</th>
                            <th>وضعیت</th>
                            <th>تاریخ ایجاد</th>
                            <th width="110">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>

                                <td class="text-truncate" style="max-width:200px;">
                                    <a href="{{ route('products.show', $product) }}">
                                        {{ $product->title }}
                                    </a>
                                </td>

                                <td>{{ number_format($product->price) }} تومان</td>
                                <td>
                                    {{ $product->discount_price ? number_format($product->discount_price) . ' تومان' : '—' }}
                                </td>
                                <td>{{ $product->stock }}</td>

                                <td>
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                        {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>

                                <td>{{ $product->created_at->format('Y-m-d') }}</td>

                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="نمایش">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="ویرایش">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline-block" onsubmit="return confirm('آیا مطمئن هستید؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-muted">هیچ محصولی یافت نشد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer py-2">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
