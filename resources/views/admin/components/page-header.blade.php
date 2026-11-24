@php
    $title = $title ?? '';
    $subtitle = $subtitle ?? null;
    $createUrl = $createUrl ?? null;
    $createLabel = $createLabel ?? 'ایجاد';
@endphp
<div class="sp-admin-pagehead">
    <div>
        <h1 class="sp-admin-pagehead__title">{{ $title }}</h1>
        @if($subtitle)
            <p class="sp-admin-pagehead__sub">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="sp-admin-pagehead__actions">
        @isset($slot){{ $slot }}@endisset
        @if($createUrl)
            <a href="{{ $createUrl }}" class="btn btn-primary btn-sm">{{ $createLabel }}</a>
        @endif
    </div>
</div>