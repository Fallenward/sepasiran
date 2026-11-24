@php
    $selectedRoles = old('roles', $selectedRoles ?? ($user->roles->pluck('id')->all() ?? []));
@endphp
<div style="display:grid;gap:14px;max-width:760px">
<div class="field" style="margin:0"><label for="name">نام</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="family">نام خانوادگی</label>
<input id="family" type="text" name="family" class="form-control @error('family') is-invalid @enderror" value="{{ old('family', $user->family ?? '') }}">
@error('family')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="email">ایمیل</label>
<input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
@error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="phone">تلفن</label>
<input id="phone" type="text" name="phone" class="form-control num @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone ?? '') }}">
@error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="password">رمز عبور @if($user->exists)<span class="text-muted">(خالی = بدون تغییر)</span>@endif</label>
<input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" @unless($user->exists) required @endunless autocomplete="new-password">
@error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="password_confirmation">تکرار رمز عبور</label>
<input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>

<div class="field" style="margin:0"><label for="address">آدرس</label>
<input id="address" type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address ?? '') }}">
@error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="wallet">کیف پول</label>
<input id="wallet" type="number" min="0" name="wallet" class="form-control num @error('wallet') is-invalid @enderror" value="{{ old('wallet', $user->wallet ?? 0) }}">
@error('wallet')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="bio">بیو</label>
<textarea id="bio" name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $user->bio ?? '') }}</textarea>
@error('bio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<label style="display:flex;gap:8px;align-items:center;margin:0">
<input type="hidden" name="is_admin" value="0">
<input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin ?? false))>
ادمین سیستم
</label>

<div class="field" style="margin:0">
<label>نقش‌ها</label>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:8px">
@foreach(($roles ?? []) as $role)
<label style="display:flex;gap:8px;align-items:center;margin:0">
<input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, (array) $selectedRoles, true))>
{{ $role->name }}
</label>
@endforeach
</div>
@error('roles')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>