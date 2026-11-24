@php
    $supportBoot = [
        'bootstrapUrl' => route('frontend.support.api.bootstrap'),
        'ticketsUrl' => route('frontend.support.api.tickets'),
        'loginUrl' => route('login', ['redirect' => url()->current()]),
        'csrf' => csrf_token(),
        'userId' => auth()->id(),
        'reverb' => [
            'key' => config('broadcasting.connections.reverb.key', env('REVERB_APP_KEY')),
            'host' => env('REVERB_HOST', '127.0.0.1'),
            'port' => (int) env('REVERB_PORT', 8080),
            'scheme' => env('REVERB_SCHEME', 'http'),
            'enabled' => config('broadcasting.default') === 'reverb',
        ],
    ];
@endphp
<div id="sp-support-root" data-config='@json($supportBoot)' hidden></div>