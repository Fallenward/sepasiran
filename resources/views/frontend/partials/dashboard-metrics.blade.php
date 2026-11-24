@if(!empty($metrics) && count($metrics))
<section class="sp-dash__metrics" aria-label="شاخص‌ها">
    @foreach($metrics as $metric)
        @php $tag = !empty($metric['url']) ? 'a' : 'article'; @endphp
        <{{ $tag }} class="sp-dash__metric {{ !empty($metric['url']) ? 'sp-dash__metric--link' : '' }}"
           @if(!empty($metric['url'])) href="{{ $metric['url'] }}" @endif>
            <div class="sp-dash__metric-top">
                <p class="sp-dash__metric-label">{{ $metric['label'] }}</p>
                @if(!empty($metric['icon']))
                    <span class="sp-dash__metric-icon"><i class="{{ $metric['icon'] }}"></i></span>
                @endif
            </div>
            <p class="sp-dash__metric-value num">{{ $metric['value'] }}</p>
            @if(!empty($metric['hint']))
                <p class="sp-dash__metric-hint">{{ $metric['hint'] }}</p>
            @endif
        </{{ $tag }}>
    @endforeach
</section>
@endif
