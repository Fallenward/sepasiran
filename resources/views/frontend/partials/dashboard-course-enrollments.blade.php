<section class="sp-dash__panel">
    <header class="sp-dash__panel-head">
        <h2>دوره‌های اخیر</h2>
        <a href="{{ route('account.course-enrollments.index') }}">همه دوره‌ها</a>
    </header>
    <div class="sp-dash__list">
        @forelse($courseEnrollments as $enrollment)
            <article class="sp-dash__row">
                <span class="sp-dash__row-icon"><i class="fas fa-play-circle"></i></span>
                <div>
                    <strong>{{ $enrollment->course?->title ?? 'دوره' }}</strong>
                    <p class="sp-dash__muted num">{{ $enrollment->enrollment_code }}</p>
                </div>
                <div class="sp-dash__row-meta">
                    <span class="sp-dash__chip is-active">فعال</span>
                    @if($enrollment->course?->slug)
                        <a class="sp-btn sp-btn--ghost" href="{{ route('frontend.courses.show', $enrollment->course->slug) }}">باز کردن</a>
                    @endif
                </div>
            </article>
        @empty
            <p class="sp-dash__empty-inline">هنوز دوره‌ای نخریده‌اید.</p>
        @endforelse
    </div>
</section>
