@extends('admin.layout')

@section('content')
    <h1>Edit Permission: {{ $permission->name }}</h1>

    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary mb-3">Back to Permissions</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name">Permission Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $permission->slug) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Permission</button>
    </form>
@endsection
