<div style="display:grid;gap:14px;max-width:640px">
<div class="field" style="margin:0"><label for="name">نام</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $permission->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="slug">اسلاگ</label>
<input id="slug" type="text" name="slug" class="form-control num @error('slug') is-invalid @enderror" value="{{ old('slug', $permission->slug ?? '') }}" required placeholder="مثلاً users.create">
@error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>