@php
    $vueProps = [
        'title' => $title,
        'breadcrumbs' => collect($breadcrumbs ?? [])->map(fn ($c) => [
            'label' => $c['label'] ?? '',
            'route' => $c['route'] ?? null,
        ])->values()->all(),
    ];
@endphp

<div data-vue="page-header" data-props='@json($vueProps)' class="{{ $extraClasses }}"></div>

