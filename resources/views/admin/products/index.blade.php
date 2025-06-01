@extends('admin.layout')

@section('template_title', 'مدیریت محصولات')

@section('content')
    <div class="container-fluid">

        @if ($message = Session::get('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ $message }}',
                        showConfirmButton: false,
                        timer: 2500,
                        toast: true,
                        position: 'top-end'
                    });
                });
            </script>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between flex-wrap">
                <h5 class="mb-0">{{ __('محصولات') }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">ایجاد محصول جدید</a>
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
                                <th>برند</th>
                                <th>قیمت</th>
                                <th>تخفیف</th>
                                <th>موجودی</th>
                                <th>وضعیت</th>
                                <th>ایجادکننده</th>
                                <th>تاریخ ایجاد</th>
                                <th width="110">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                    <td class="text-truncate" style="max-width:180px;">
                                        <a href="{{ route('admin.products.show', $product) }}">
                                            {{ $product->title }}
                                        </a>
                                    </td>
                                    <td>{{ $product->category?->name ?? '—' }}</td>
                                    <td>{{ $product->brand_name ?? '—' }}</td>
                                    <td>{{ number_format($product->price) }} تومان</td>
                                    <td>
                                        @if($product->discount_price)
                                            {{ number_format($product->discount_price) }} تومان
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                            {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                                        </span>
                                    </td>
                                    <td>{{ $product->creator?->name ?? '—' }}</td>
                                    <td>{{ $product->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete Button & Hidden Form -->
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="11">هیچ محصولی یافت نشد.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer py-2">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection


    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(productId) {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این عملیات غیرقابل بازگشت است!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'بله، حذف شود!',
                cancelButtonText: 'لغو'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + productId).submit();
                }
            });
        }
    </script>

