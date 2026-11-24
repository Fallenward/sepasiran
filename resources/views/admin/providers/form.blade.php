<div style="display:grid;gap:14px;max-width:720px">
<div class="field" style="margin:0"><label for="name">نام</label>
<input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $provider->name ?? '') }}" required>
@error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="family">نام خانوادگی</label>
<input id="family" type="text" name="family" class="form-control @error('family') is-invalid @enderror" value="{{ old('family', $provider->family ?? '') }}">
@error('family')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="email">ایمیل</label>
<input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $provider->email ?? '') }}" required>
@error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="phone">تلفن</label>
<input id="phone" type="text" name="phone" class="form-control num @error('phone') is-invalid @enderror" value="{{ old('phone', $provider->phone ?? '') }}">
@error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="password">رمز عبور @if(!empty($provider->exists))<span class="text-muted">(خالی = بدون تغییر)</span>@endif</label>
<input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" @unless(!empty($provider->exists)) required @endunless autocomplete="new-password">
@error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="password_confirmation">تکرار رمز عبور</label>
<input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>

<div class="field" style="margin:0"><label for="city_id">شهر</label>
<select id="city_id" name="city_id" class="form-control @error('city_id') is-invalid @enderror">
<option value="">—</option>
@foreach(($cities ?? []) as $city)
<option value="{{ $city->id }}" @selected((string) old('city_id', $provider->city_id ?? '') === (string) $city->id)>{{ $city->name }}</option>
@endforeach
</select>
@error('city_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div class="field" style="margin:0"><label for="bio">بیو / توضیحات</label>
<textarea id="bio" name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $provider->bio ?? '') }}</textarea>
@error('bio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>

<div><button type="submit" class="btn btn-primary">ذخیره</button></div>
</div>