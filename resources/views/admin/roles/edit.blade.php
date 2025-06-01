@extends('admin.layout')

@section('content')
    <h1>Edit Role: {{ $role->name }}</h1>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $role->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <h4>Permissions</h4>
        <div class="mb-3">
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="permissions[]" value="{{ $permission->id }}" id="perm-{{ $permission->id }}"
                           {{ (in_array($permission->id, old('permissions', $rolePermissions))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm-{{ $permission->id }}">
                        {{ $permission->slug }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
