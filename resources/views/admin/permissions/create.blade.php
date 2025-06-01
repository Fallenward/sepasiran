@extends('admin.layout')

@section('content')
    <h1>Create Permission</h1>

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

    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="name">Permission Name (e.g. users.create)</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="slug">Slug (unique identifier, e.g. users.create)</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Permission</button>
    </form>
@endsection
