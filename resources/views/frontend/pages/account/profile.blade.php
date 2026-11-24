@extends('frontend.layouts.app')

@section('title', 'ویرایش پروفایل — سپاسیران')

@section('content')
<div class="sp-dash sp-dash--customer">
    <div class="sp-dash__inner">
                @include('frontend.partials.dashboard-nav')
<div class="sp-dash__brand" aria-label="برند">
            <p class="sp-dash__brand-fa">سپاسیران</p>
            <p class="sp-dash__brand-en latin">Profile</p>
        </div>

        <section class="sp-dash__hero">
            <div class="sp-dash__hero-main">
                <div class="sp-dash__avatar sp-dash__avatar--lg">
                    @if($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="">
                    @else
                        {{ mb_substr($user->name ?? 'س', 0, 1) }}{{ mb_substr($user->family ?? '', 0, 1) }}
                    @endif
                </div>
                <div>
                    <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
                    <h1 class="sp-dash__title">ویرایش پروفایل</h1>
                    <p class="sp-dash__lede">نام، تماس، تصویر و رمز عبور حساب خود را مدیریت کنید.</p>
                </div>
            </div>
            <aside class="sp-dash__hero-cta">
                <div>
                    <strong>بازگشت</strong>
                    <p>به داشبورد نقش خود برگردید.</p>
                </div>
                <a class="sp-btn sp-btn--ghost" href="{{ $backUrl }}">داشبورد</a>
            </aside>
        </section>

        @if(session('success'))
            <div class="sp-dash__flash">{{ session('success') }}</div>
        @endif

        <form class="panel sp-dash__panel" action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="sp-dash__photo-field">
                <div class="sp-dash__avatar sp-dash__avatar--lg" aria-hidden="true">
                    @if($user->avatarUrl())
                        <img id="spAvatarPreview" src="{{ $user->avatarUrl() }}" alt="">
                    @else
                        <img id="spAvatarPreview" src="" alt="" hidden>
                        <span id="spAvatarFallback">{{ mb_substr($user->name ?? 'س', 0, 1) }}</span>
                    @endif
                </div>
                <div class="field" style="flex:1;margin:0">
                    <label for="profile_picture">تصویر پروفایل</label>
                    <input id="profile_picture" type="file" name="profile_picture" accept="image/*">
                    <p class="sp-dash__muted" style="margin-top:8px">JPG یا PNG تا ۲ مگابایت.</p>
                    @error('profile_picture')<p class="auth-alert" style="margin-top:8px">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field">
                <label for="name">نام</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="family">نام خانوادگی</label>
                <input id="family" type="text" name="family" value="{{ old('family', $user->family) }}" required>
                @error('family')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="phone">موبایل</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="email">ایمیل</label>
                <input id="email" type="email" name="email" value="{{ old('email', str_ends_with((string) $user->email, '@phone.sepasiran.local') ? '' : $user->email) }}">
                @error('email')<p class="auth-alert">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="address">آدرس</label>
                <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}">
            </div>
            <div class="field">
                <label for="bio">درباره من</label>
                <textarea id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <div class="field">
                <label for="password">رمز عبور جدید</label>
                <input id="password" type="password" name="password" autocomplete="new-password">
            </div>
            <div class="field">
                <label for="password_confirmation">تکرار رمز عبور</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
            </div>

            <div class="sp-dash__form-actions">
                <button type="submit" class="sp-btn sp-btn--primary">ذخیره تغییرات</button>
                <a class="sp-btn sp-btn--ghost" href="{{ $backUrl }}">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const input = document.getElementById('profile_picture');
  const preview = document.getElementById('spAvatarPreview');
  const fallback = document.getElementById('spAvatarFallback');
  if (!input || !preview) return;
  input.addEventListener('change', () => {
    const file = input.files && input.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.hidden = false;
    if (fallback) fallback.hidden = true;
  });
})();
</script>
@endpush
