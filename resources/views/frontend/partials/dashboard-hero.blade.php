@php
    $displayName = trim(($user->name ?? '') . ' ' . ($user->family ?? ''));
    $roleLabel = $roleLabel ?? ($user->primaryRoleLabel() ?? 'حساب');
    $initials = mb_substr($user->name ?? 'س', 0, 1) . mb_substr($user->family ?? '', 0, 1);
    $avatar = $user->profile_picture ?? $user->avatar_url ?? null;
@endphp
<div class="sp-dash__brand" aria-label="برند">
    <p class="sp-dash__brand-fa">سپاسیران</p>
    <p class="sp-dash__brand-en latin">Sepasiran</p>
</div>

<section class="sp-dash__hero">
    <div class="sp-dash__hero-main">
        <div class="sp-dash__avatar" aria-hidden="true">
            @if($avatar)
                <img src="{{ $avatar }}" alt="">
            @else
                {{ $initials !== '' ? $initials : 'س' }}
            @endif
        </div>
        <div>
            <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
            <h1 class="sp-dash__title">سلام، {{ $displayName !== '' ? $displayName : 'کاربر' }}</h1>
            <p class="sp-dash__lede">{{ $lede ?? 'خلاصه وضعیت حساب و کارهای بعدی شما.' }}</p>
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
