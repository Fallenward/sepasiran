@php
    $displayName = trim(($user->name ?? '') . ' ' . ($user->family ?? ''));
    $roleLabel = $roleLabel ?? ($user->primaryRoleLabel() ?? 'حساب');
    $initials = mb_substr($user->name ?? 'س', 0, 1) . mb_substr($user->family ?? '', 0, 1);
    $avatar = method_exists($user, 'avatarUrl') ? $user->avatarUrl() : ($user->profile_picture ?? null);
    $profileUrl = method_exists($user, 'profileEditRoute') ? $user->profileEditRoute() : route('account.profile.edit');
@endphp
<div class="sp-dash__brand" aria-label="برند">
    <p class="sp-dash__brand-fa">سپاسیران</p>
    <p class="sp-dash__brand-en latin">Sepasiran</p>
</div>

<section class="sp-dash__hero">
    <div class="sp-dash__hero-main">
        <a class="sp-dash__avatar sp-dash__avatar--editable" href="{{ $profileUrl }}" title="ویرایش تصویر پروفایل">
            @if($avatar)
                <img src="{{ $avatar }}" alt="">
            @else
                {{ $initials !== '' ? $initials : 'س' }}
            @endif
            <span class="sp-dash__avatar-edit"><i class="fas fa-camera"></i></span>
        </a>
        <div>
            <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
            <h1 class="sp-dash__title">سلام، {{ $displayName !== '' ? $displayName : 'کاربر' }}</h1>
            <p class="sp-dash__lede">{{ $lede ?? 'خلاصه وضعیت حساب و کارهای بعدی شما.' }}</p>
            <div class="sp-dash__hero-links">
                <a class="sp-btn sp-btn--ghost" href="{{ $profileUrl }}" style="min-height:38px;padding:8px 14px">ویرایش پروفایل</a>
                <a class="sp-btn sp-btn--ghost" href="{{ route('account.notifications.index') }}" style="min-height:38px;padding:8px 14px">اعلان‌ها</a>
            </div>
        </div>
    </div>

    @if(!empty($primaryAction))
        <aside class="sp-dash__hero-cta">
            <div>
                <strong>{{ $ctaTitle ?? 'اقدام سریع' }}</strong>
                <p>{{ $ctaHint ?? 'از اینجا مستقیم وارد کار اصلی حساب خود شوید.' }}</p>
            </div>
            <a class="sp-btn sp-btn--primary" href="{{ $primaryAction['url'] }}">{{ $primaryAction['label'] }}</a>
        </aside>
    @endif
</section>
