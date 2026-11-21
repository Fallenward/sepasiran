@props([
    'resume' => ['total' => 0, 'items' => collect()],
    'roleLabel' => 'ارائه‌دهنده',
])
@php
    $items = collect($resume['items'] ?? []);
    $total = (int) ($resume['total'] ?? $items->count());
@endphp
<section class="sp-provider__panel sp-provider__resume">
    <div class="sp-provider__resume-head">
        <h2>سوابق جلسات</h2>
        @if($total > 0)
            <span class="sp-provider__chip is-accent num">{{ $total }} جلسه تکمیل‌شده</span>
        @endif
    </div>
    <p class="sp-provider__cta-note" style="margin:0 0 12px">رزومه کاری این {{ $roleLabel }} بر اساس جلسات انجام‌شده در سپاس‌ایران.</p>

    @if($items->isNotEmpty())
        <ol class="sp-provider__resume-list">
            @foreach($items as $row)
                <li class="sp-provider__resume-item">
                    <div class="sp-provider__resume-mark" aria-hidden="true"></div>
                    <div class="sp-provider__resume-body">
                        <div class="sp-provider__resume-top">
                            <strong>{{ $row['title'] }}</strong>
                            <span class="sp-provider__chip">{{ $row['status_label'] }}</span>
                        </div>
                        <p>
                            @if(!empty($row['date']))
                                <span class="num">{{ $row['date'] }}</span>
                                @if(!empty($row['slot'])) · {{ $row['slot'] }}@endif
                            @endif
                            @if(!empty($row['city'])) · {{ $row['city'] }}@endif
                            @if(!empty($row['breed'])) · {{ $row['breed'] }}@endif
                            @if(!empty($row['dog'])) · {{ $row['dog'] }}@endif
                        </p>
                    </div>
                </li>
            @endforeach
        </ol>
        @if($total > $items->count())
            <p class="sp-provider__empty">و {{ $total - $items->count() }} جلسه دیگر…</p>
        @endif
    @else
        <p class="sp-provider__empty">هنوز جلسه تکمیل‌شده‌ای ثبت نشده است. با رزرو مستقیم، اولین سابقه اینجا می‌آید.</p>
    @endif
</section>