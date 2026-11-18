@php
    $displayName = trim(($user->name ?? '') . ' ' . ($user->family ?? ''));
    $roleLabel = $roleLabel ?? ($user->primaryRoleLabel() ?? 'حساب');
@endphp
<section class="sp-dash__hero">
    <div class="sp-dash__hero-copy">
        <p class="sp-dash__eyebrow">{{ $roleLabel }}</p>
        <h1 class="sp-dash__title">سلام، {{ $displayName !== '' ? $displayName : 'کاربر' }}</h1>
        <p class="sp-dash__lede">{{ $lede ?? 'خلاصه وضعیت حساب و کارهای بعدی شما.' }}</p>
    </div>
    @if(!empty($primaryAction))
        <a class="sp-btn sp-btn--primary" href="{{ $primaryAction['url'] }}">{{ $primaryAction['label'] }}</a>
    @endif
</section>
