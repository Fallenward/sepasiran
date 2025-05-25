<div class="row">
@forelse ($courses as $course)
    <div class="col-md-4 mb-4 course-item">
        <div class="card h-100">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $course->title }}</h5>
                <p class="card-text text-truncate">{{ $course->short_description }}</p>
                <p class="mb-1"><strong>استاد:</strong> {{ $course->user->name ?? '-' }}</p>
                <p class="mb-1"><strong>دسته‌بندی:</strong> {{ $course->category->name ?? '-' }}</p>
                <p class="mb-1"><strong>پایه تحصیلی:</strong> {{ $course->level->name ?? '-' }}</p>
                <p class="mb-1">
                    <strong>قیمت:</strong>
                    @if($course->price == 0)
                        رایگان
                    @elseif($course->discount_price && $course->discount_price < $course->price)
                        <del>{{ number_format($course->price) }} تومان</del>
                        <span class="text-danger">{{ number_format($course->discount_price) }} تومان</span>
                    @else
                        {{ number_format($course->price) }} تومان
                    @endif
                </p>
                <a href="{{ route('frontend.courses.show', $course->slug) }}" class="btn btn-primary mt-auto">مشاهده دوره</a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <p class="text-center">هیچ دوره‌ای یافت نشد.</p>
    </div>
@endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $courses->links() }}
</div>
