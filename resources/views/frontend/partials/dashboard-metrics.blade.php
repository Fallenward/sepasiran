@if(!empty($metrics) && count($metrics))
<section class="sp-dash__metrics" aria-label="شاخص‌ها">
    @foreach($metrics as $metric)
        <article class="sp-dash__metric">
            <p class="sp-dash__metric-label">{{ $metric['label'] }}</p>
            <p class="sp-dash__metric-value latin">{{ $metric['value'] }}</p>
            @if(!empty($metric['hint']))
                <p class="sp-dash__metric-hint">{{ $metric['hint'] }}</p>
            @endif
        </article>
    @endforeach
</section>
@endif
