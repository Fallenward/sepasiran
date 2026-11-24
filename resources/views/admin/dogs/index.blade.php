@extends('admin.layout')

@section('title', 'سگ‌ها')

@section('content')
@include('admin.components.page-header', [
    'title' => 'سگ‌ها',
    'subtitle' => 'مدیریت آگهی‌ها و وضعیت واگذاری.',
    'createUrl' => route('admin.dogs.create'),
    'createLabel' => 'سگ جدید',
])

@if ($message = Session::get('success'))
    <div class="alert alert-success">{{ $message }}</div>
@endif

<div class="card sp-admin-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover sp-admin-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>نژاد</th>
                    <th>وضعیت</th>
                    <th>سن</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @php $i = ($dogs->currentPage() - 1) * $dogs->perPage(); @endphp
                @forelse($dogs as $dog)
                    <tr>
                        <td class="num">{{ ++$i }}</td>
                        <td>{{ $dog->title ?? '—' }}</td>
                        <td>{{ $dog->breed->name ?? '—' }}</td>
                        <td><span class="badge badge-secondary">{{ $dog->status ?? '—' }}</span></td>
                        <td class="num">{{ $dog->age ?? '—' }}</td>
                        <td class="sp-admin-actions">
                            <a class="btn btn-sm btn-outline-light" href="{{ route('admin.dogs.show', $dog->id) }}">نمایش</a>
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.dogs.edit', $dog->id) }}">ویرایش</a>
                            <form action="{{ route('admin.dogs.destroy', $dog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">سگی ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {!! $dogs->withQueryString()->links() !!}
    </div>
</div>
@endsection