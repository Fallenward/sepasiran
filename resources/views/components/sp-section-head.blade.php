@props([
    'title',
    'subtitle' => null,
    'icon' => 'spark',
    'actionHref' => null,
    'actionLabel' => null,
])
<div {{ $attributes->merge(['class' => 'section__head sp-section-head'.($actionHref ? ' sp-section-head-row' : '')]) }}>
    <div class="sp-section-head__copy">
        <div class="sp-section-head__title-row">
            <span class="sp-section-head__icon" aria-hidden="true">
                <x-sp-icon :name="$icon" />
            </span>
            <h2>{{ $title }}</h2>
        </div>
        @if($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
    </div>
    @if($actionHref && $actionLabel)
        <a class="btn btn-ghost" href="{{ $actionHref }}">{{ $actionLabel }}</a>
    @endif
</div>