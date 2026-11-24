@php $selected = old('permissions', $rolePermissions ?? []); @endphp
<div style="display:grid;gap:14px;max-width:860px">
<div class="field" style="margin:0"><label for="name">نام نقش</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0">
<label>مجوزها</label>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:8px;max-height:420px;overflow:auto;padding:8px;border:1px solid rgba(255,255,255,.08);border-radius:8px">
@forelse(($permissions ?? []) as $permission)
<label style="display:flex;gap:8px;align-items:flex-start;margin:0">
<input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, (array) $selected, true))>
<span><strong>{{ $permission->name }}</strong><br><span class="text-muted small num">{{ $permission->slug }}</span></span>
</label>
@empty
<span class="text-muted">مجوزی تعریف نشده.</span>
@endforelse
</div>
</div>
<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>