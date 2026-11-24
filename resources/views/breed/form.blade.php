<div style="display:grid;gap:14px;max-width:640px">
<div class="field" style="margin:0"><label for="name">نام نژاد</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $breed->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="description">توضیحات</label>
<textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $breed->description ?? '') }}</textarea>
@error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>