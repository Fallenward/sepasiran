@extends('admin.layout')

@section('template_title')
    Dogs
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span id="card_title">{{ __('Dogs') }}</span>
                    <a href="{{ route('admin.dogs.create') }}" class="btn btn-primary btn-sm">
                        {{ __('Create New Dog') }}
                    </a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success m-4">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Breed</th>
                                    <th>Color</th>
                                    <th>Age</th>
                                    <th>Cover Type</th>
                                    <th>Eye Color</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = ($dogs->currentPage() - 1) * $dogs->perPage();
                                @endphp
                                @foreach ($dogs as $dog)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $dog->title ?? '-' }}</td>
                                        <td>{{ $dog->breed->name ?? 'No breed' }}</td>
                                        <td>{{ $dog->color ?? '-' }}</td>
                                        <td>{{ $dog->age ?? '-' }}</td>
                                        <td>{{ $dog->cover_type ?? '-' }}</td>
                                        <td>{{ $dog->eye_color ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.dogs.destroy', $dog->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary" href="{{ route('admin.dogs.show', $dog->id) }}">
                                                    <i class="fa fa-fw fa-eye"></i> Show
                                                </a>
                                                <a class="btn btn-sm btn-success" href="{{ route('admin.dogs.edit', $dog->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> Edit
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">
                                                    <i class="fa fa-fw fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $dogs->withQueryString()->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
