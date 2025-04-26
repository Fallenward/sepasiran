@foreach ($blogs as $blog)
    <tr>
        <td>{{ $blog->title }}</td>
        <td>{{ $blog->category->name }}</td>
        <td>{{ $blog->user->name }}</td>
        <td>
            <span class="badge badge-info" data-toggle="tooltip" title="تعداد نظرات">{{ $blog->comments()->count() }}</span>
        </td>
        <td>{{ $blog->created_at_jalali }}</td>
        <td>
            @if($blog->status === 'draft')
                <span class="badge badge-warning">
                    <i class="fas fa-pencil-alt"></i> پیش‌نویس
                </span>
            @elseif($blog->status === 'published')
                <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> منتشر شده
                </span>
            @endif
        </td>

        <td>
            <form class="delete-blog-form" action="{{ route('blogs.destroy', $blog->id) }}" method="POST">
                <a class="btn btn-sm btn-primary" target="_blank" href="{{ route('frontend.blogs.show', $blog->slug) }}">
                    <i class="fa fa-fw fa-eye"></i> مشاهده
                </a>
                <a class="btn btn-sm btn-success" href="{{ route('blogs.edit', $blog->id) }}">
                    <i class="fa fa-fw fa-edit"></i> ویرایش
                </a>
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm delete-blog-btn" data-title="{{ $blog->title }}">
                    <i class="fa fa-fw fa-trash"></i> حذف
                </button>
            </form>
        </td>
    </tr>
@endforeach

<!-- اسکریپت تأیید حذف با `SweetAlert2` -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-blog-btn').forEach(button => {
                button.addEventListener('click', function () {
                    let form = this.closest('.delete-blog-form');
                    let blogTitle = this.getAttribute('data-title');

                    Swal.fire({
                        title: "آیا از حذف این وبلاگ مطمئن هستید؟",
                        text: "مقاله '" + blogTitle + "' حذف خواهد شد!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "بله، حذف شود!",
                        cancelButtonText: "لغو"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // فعال کردن tooltip برای تعداد نظرات
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>
@endpush
