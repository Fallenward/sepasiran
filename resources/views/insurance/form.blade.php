<div style="display:grid;gap:14px;max-width:720px">
<div class="field" style="margin:0"><label for="name">نام</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $insurance->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="slug">اسلاگ</label>
<input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $insurance->slug ?? '') }}">
@error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="description">توضیحات</label>
<textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $insurance->description ?? '') }}</textarea>
@error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="logo">مسیر لوگو</label>
<input id="logo" type="text" name="logo" class="form-control @error('logo') is-invalid @enderror" value="{{ old('logo', $insurance->logo ?? '') }}" placeholder="images/... یا storage/...">
@error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="contact_phone">تلفن</label>
<input id="contact_phone" type="text" name="contact_phone" class="form-control num @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $insurance->contact_phone ?? '') }}">
@error('contact_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="contact_email">ایمیل</label>
<input id="contact_email" type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $insurance->contact_email ?? '') }}">
@error('contact_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div class="field" style="margin:0"><label for="website">وب‌سایت</label>
<input id="website" type="text" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $insurance->website ?? '') }}">
@error('website')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
<div style="display:flex;gap:18px;flex-wrap:wrap">
<label style="display:flex;gap:8px;align-items:center;margin:0"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $insurance->is_active ?? true))> فعال</label>
<label style="display:flex;gap:8px;align-items:center;margin:0"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $insurance->is_featured ?? false))> ویژه</label>
</div>
<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>