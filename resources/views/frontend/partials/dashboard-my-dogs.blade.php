@php
    $myDogs = $myDogs ?? collect();
    if ((!isset($myDogs) || $myDogs === null) && auth()->check()) {
        $myDogs = \App\Models\Dog::query()
            ->where('user_id', auth()->id())
            ->with(['breed', 'media'])
            ->latest()
            ->limit(6)
            ->get();
    }
    $myDogs = collect($myDogs);
    $statusLabels = [
        'available' => 'برای فروش',
        'ready' => 'آماده',
        'owned' => 'مالکیت شخصی',
        'sold' => 'فروخته‌شده',
        'pending' => 'در انتظار',
        'waiting' => 'در صف',
        'for_sale' => 'برای فروش',
    ];
@endphp
<section class="sp-dash__panel sp-dash__my-dogs" aria-label="سگ‌های من">
    <header class="sp-dash__panel-head">
        <h2>سگ‌های من</h2>
        <a href="{{ route('account.dogs.index') }}">مدیریت همه</a>
    </header>

    @if($myDogs->isNotEmpty())
        <div class="sp-dash__list">
            @foreach($myDogs as $dog)
                @php
                    $img = method_exists($dog, 'primaryImageUrl') ? $dog->primaryImageUrl() : null;
                    if (! $img && !empty($dog->image)) {
                        $img = str_starts_with($dog->image, 'http') || str_starts_with($dog->image, '/')
                            ? $dog->image
                            : asset($dog->image);
                    }
                    $img = $img ?: asset('images/demo/dogs/dog-02.jpg');
                    $breed = method_exists($dog, 'breedName') ? $dog->breedName() : (optional($dog->breed)->name ?? '—');
                    $st = $statusLabels[$dog->status] ?? ($dog->status ?: '—');
                @endphp
                <article class="sp-dash__row sp-dash__row--dog">
                    <img class="sp-dash__dog-thumb" src="{{ $img }}" alt="{{ $dog->title }}">
                    <div>
                        <strong>{{ $dog->title ?? ('سگ #'.$dog->id) }}</strong>
                        <p class="sp-dash__muted">{{ $breed }} · {{ $st }}</p>
                    </div>
                    <div class="sp-dash__row-meta">
                        <a class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" href="{{ route('account.dogs.edit', $dog) }}">ویرایش</a>
                        <a class="sp-btn sp-btn--ghost" style="min-height:34px;padding:6px 12px" href="{{ route('frontend.dog-supply.show', $dog->id) }}">نمایش</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
            <a class="sp-btn sp-btn--primary" href="{{ route('account.dogs.create') }}">افزودن سگ</a>
            <a class="sp-btn sp-btn--ghost" href="{{ route('account.dogs.index') }}">همه سگ‌ها</a>
        </div>
    @else
        <div class="sp-dash__empty">
            <div class="sp-dash__empty-visual" style="background-image:linear-gradient(160deg,rgba(12,18,16,.25),rgba(7,10,8,.7)),url('/images/demo/dogs/dog-05.jpg')" aria-hidden="true"></div>
            <p>هنوز سگی در حساب شما نیست.</p>
            <a class="sp-btn sp-btn--primary" href="{{ route('account.dogs.create') }}">ثبت اولین سگ</a>
        </div>
    @endif
</section>