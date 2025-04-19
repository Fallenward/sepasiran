@if($count)
    <div class="row">
        @foreach ($courses as $course)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100">
                    <!-- Course Image -->
                    <img src="{{ asset('storage/' . $course->image) }}" class="card-img-top" alt="{{ $course->name }}">

                    <div class="card-body d-flex flex-column justify-content-between">
                        <!-- Course Title -->
                        <div class="card-title d-flex justify-content-between">
                            <h5>{{ $course->title }}</h5>
                            <div>
                                <span class="btn btn-outline-primary course-credit text-muted">
                                    {{ $course->getPaymentTypeLabel() }}
                                </span>
                            </div>
                        </div>

                        <!-- Course Category -->
                        <div class="card-text">
                            @foreach($course->categories as $category)
                                <button class="category-badge">{{ $category->name }}</button>
                            @endforeach
                        </div>

                        <!-- Course Price and Payment Type -->
                        <div class="card-text">
                            <div class="course-price">
                                قیمت: {!! $course->getDisplayPrice() !!} {!! $course->getPriceUnit() !!}
                            </div>

                            <!-- View Package Button -->
                            <div class="mt-auto">
                                <a href="{{ route('frontend.courses.show', $course->slug) }}" class="btn btn-primary w-100 mt-2">مشاهده پکیج</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
