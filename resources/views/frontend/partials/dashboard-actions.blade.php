@if(!empty($actions) && count($actions))
<section class="sp-dash__actions" aria-label="میانبرها">
    @foreach($actions as $action)
        <a class="sp-dash__action" href="{{ $action['url'] }}">
            @if(!empty($action['icon']))
                <span class="sp-dash__action-icon"><i class="{{ $action['icon'] }}"></i></span>
            @endif
            <span class="sp-dash__action-label">{{ $action['label'] }}</span>
            @if(!empty($action['hint']))
                <span class="sp-dash__action-hint">{{ $action['hint'] }}</span>
            @endif
        </a>
    @endforeach
</section>
@endif
