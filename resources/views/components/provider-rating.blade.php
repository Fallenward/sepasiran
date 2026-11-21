@props([
    'avg' => 0,
    'count' => 0,
    'showCount' => true,
])
@php
    $avg = max(0, min(5, (float) $avg));
    $full = (int) floor($avg);
    $half = ($avg - $full) >= 0.5;
@endphp
<div class="sp-rating" title="{{ number_format($avg, 1) }} از ۵">
    <div class="sp-rating__stars" aria-hidden="true">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $full)
                <i class="fas fa-star"></i>
            @elseif($half && $i === $full + 1)
                <i class="fas fa-star-half-alt"></i>
            @else
                <i class="far fa-star"></i>
            @endif
        @endfor
    </div>
    <span class="sp-rating__score num">{{ $count > 0 ? number_format($avg, 1) : '—' }}</span>
    @if($showCount)
        <span class="sp-rating__count">{{ $count > 0 ? '('.$count.' نظر)' : 'بدون امتیاز' }}</span>
    @endif
</div>