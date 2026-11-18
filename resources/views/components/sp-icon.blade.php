@props(['name' => 'spark', 'class' => ''])
@php
    $icons = [
        'start' => '<path d="M12 3v3M12 18v3M3 12h3M18 12h3"/><circle cx="12" cy="12" r="4"/><path d="m15.5 8.5 2-2M6.5 17.5l2-2M8.5 6.5l-2-2M17.5 17.5l-2-2"/>',
        'dogs' => '<path d="M5 14c1.5-4 4-6 7-6s5.5 2 7 6"/><path d="M8 10c-.8-2.2-2.2-3.5-4-3.2.4 1.8 1.5 3 3.2 3.5"/><path d="M16 10c.8-2.2 2.2-3.5 4-3.2-.4 1.8-1.5 3-3.2 3.5"/><path d="M9 15.5c.7.8 1.7 1.3 3 1.3s2.3-.5 3-1.3"/><circle cx="9.2" cy="12.2" r=".7"/><circle cx="14.8" cy="12.2" r=".7"/>',
        'services' => '<path d="M8 7h8v3a4 4 0 0 1-8 0V7z"/><path d="M9 7V5.8A1.8 1.8 0 0 1 10.8 4h2.4A1.8 1.8 0 0 1 15 5.8V7"/><path d="M10 14v5M14 14v5M8 19h8"/>',
        'steps' => '<path d="M5 19h5v-5H5v5zM10 14h5V9h-5v5zM15 9h4V5h-4v4z"/>',
        'store' => '<path d="M4 9h16l-1.2 10.2a2 2 0 0 1-2 1.8H7.2a2 2 0 0 1-2-1.8L4 9z"/><path d="M8 9V7a4 4 0 0 1 8 0v2"/>',
        'trust' => '<path d="M12 3 5 6v5c0 4.5 3.1 7.8 7 9 3.9-1.2 7-4.5 7-9V6l-7-3z"/><path d="m9.2 12 1.9 1.9 3.7-3.8"/>',
        'news' => '<path d="M6 5h9a2 2 0 0 1 2 2v12H8a2 2 0 0 1-2-2V5z"/><path d="M17 8h1.5A1.5 1.5 0 0 1 20 9.5V16a3 3 0 0 1-3 3"/><path d="M9 9h5M9 12h5M9 15h3"/>',
        'cta' => '<path d="M5 12h11"/><path d="m12 7 5 5-5 5"/><path d="M5 7v10"/>',
        'coach' => '<path d="M8 14a4 4 0 1 1 8 0"/><circle cx="12" cy="8" r="3"/><path d="M5 20c1.2-2.4 3.4-3.5 7-3.5s5.8 1.1 7 3.5"/>',
        'clinic' => '<path d="M8 4h8v4h4v8h-4v4H8v-4H4V8h4V4z"/>',
        'insurance' => '<path d="M12 3 5 6v5c0 4.2 2.9 7.4 7 8.7 4.1-1.3 7-4.5 7-8.7V6l-7-3z"/>',
        'course' => '<path d="M3 8.5 12 4l9 4.5-9 4.5L3 8.5z"/><path d="M7 11.2v4.1c0 .8 2.2 2.2 5 2.2s5-1.4 5-2.2v-4.1"/><path d="M21 9v6"/>',
        'spark' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6.2 6.2l2.8 2.8M15 15l2.8 2.8M17.8 6.2 15 9M9 15l-2.8 2.8"/>',
    ];
    $paths = $icons[$name] ?? $icons['spark'];
@endphp
<svg {{ $attributes->merge(['class' => trim('sp-icon '.$class), 'viewBox' => '0 0 24 24', 'aria-hidden' => 'true', 'focusable' => 'false']) }} fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">{!! $paths !!}</svg>