@if($courses->count())
    <div class="row">
        @foreach ($courses as $course)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 course-item">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . ($course->thumbnail ?? 'default-course.png')) }}" class="card-img-top" alt="{{ $course->title }}">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="card-title d-flex justify-content-between">
                            <h5>{{ $course->title }}</h5>
                            <span class="btn btn-outline-primary course-credit text-muted">
                                {{ $course->price == 0 ? 'رایگان' : 'پولی' }}
                            </span>
                        </div>
                        <div class="card-text mb-2">
                            {{ $course->category->name ?? 'بدون دسته بندی' }}
                        </div>
                        <div class="card-text">
                            <div class="course-price">
                                قیمت: {{ number_format($course->price) }} تومان
                            </div>
                            <a href="{{ route('frontend.courses.show', $course->slug) }}" class="btn btn-primary w-100 mt-2">مشاهده پکیج</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
@else
    <p class="text-center">موردی یافت نشد.</p>
@endif
