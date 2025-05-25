@extends('frontend.layouts.app')

@section('title', $title)

@php

    // Calculate total comments
    $totalComments = count($comments);

    // Initialize ratings array
    $ratings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

    // Count each rating
    foreach ($comments as $comment) {
        if (isset($ratings[$comment->rate])) {
            $ratings[$comment->rate]++;
        }
    }

    // Calculate percentage for each rating
    $ratingsPercentage = [];
    foreach ($ratings as $star => $count) {
        $ratingsPercentage[$star] = $totalComments > 0 ? round(($count / $totalComments) * 100, 2) : 0;
    }

    // Find the maximum percentage for styling
    $maxPercentage = max($ratingsPercentage);
@endphp

@section('content')

    <div class="container">
        <!-- Course Details Section -->
        <div class="d-md-flex d-block gap-2">
            <!-- Course Information -->
            <div class="col-12 col-md-7 shadow course-box p-3">
                @if(isset($course))
                    <h1 class="course-title">{{ $course->title ?? 'بدون عنوان' }}</h1>
                @else
                    <h1 class="course-title text-danger">دوره یافت نشد</h1>
                @endif

                <ul class="list-group course-details-group">
                    <li class="course-group-item">
                        <div class="course-details">
                            <span class="first-span">درس</span>
                        <span>{{ $course->field?->title ?? 'بدون رشته' }}</span>
                        </div>
                    </li>
                    <li class="course-group-item">
                        <div class="course-details">
                            <span class="first-span">پایه</span>
                    <span>{{ optional($course->field)->title ?? 'بدون رشته' }}</span>
                        </div>
                    </li>
                    <li class="course-group-item">
                        <div class="course-details">
                            <span class="first-span">استاد</span>
                            <span>{{!is_null($course) && !is_null($course->teacher) ? $course->teacher->fullname : ''}}</span>
                        </div>
                    </li>
                    @php
                        $supporCategories = [];
                    @endphp
                    <span>{{ $course->category->name ?? 'بدون دسته بندی' }}</span>

                    <li class="course-group-item">
                        <div class="course-details">
                            <span class="first-span">دسته بندی</span>
                            <span>{{implode(', ',$supporCategories)}}</span>
                        </div>
                    </li>
                </ul>

                <!-- Course Description -->
                <div class="col-12 course-description">
                    {{$course->description}}
                </div>

                <!-- Course Stats -->
                <div class="col-12 mt-5 d-flex text-center">
                    <div class="col-4 d-flex align-items-center">
                        <span class="d-block"><i class="fa fa-user-alt mx-2"></i></span>
                        <span class="d-none d-md-block">تعداد دانشجو:</span>
                        <span class="course-students-count">212</span>
                    </div>
                    <div class="col-4">
                        <div class="course-price">
                            {!! $course->getDisplayPrice() !!} {!! $course->getPriceUnit() !!}
                        </div>
                    </div>
                    <div class="col-4 course-single-payment-type">
                        <div class="btn btn-outline-primary course-credit text-muted">
                            {{ $course->getPaymentTypeLabel() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Image and Purchase Button -->
            <div class="col-12 col-md-5 shadow course-box p-3 d-flex flex-column">
                <div>
                    <img src="{{ asset('storage/' . $course->image) }}" class="img-fluid"/>
                </div>
                <div class="mt-auto">
                    <button id="addToCart" class="btn w-100 mt-2 text-white" style="background-color: #0d47a1;" @if(!auth()->check()) disabled @endif>
                        <span id="cartText">@if(auth()->check()) {{'افزودن به سبد خرید'}} @else {{'برای خرید دوره وارد شوید'}} @endif</span>
                        <span id="cartLoader" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>

            </div>
        </div>

        <div class="normal-divider"></div>

        <!-- Course Content and Comments Section -->
        <div class="d-md-flex d-block gap-2 ">
            <div class="col-12 col-md-7 shadow course-box p-3">
                <div class="underline-tabs mb-5">
                    <ul class="nav nav-tabs p-0" id="underlineTabs" role="tablist">
                        <li class="nav-item  w-50" role="presentation">
                            <button class="nav-link w-100 active" id="home-tab2" data-bs-toggle="tab"
                                    data-bs-target="#home2" type="button" role="tab">
                                محتوا
                            </button>
                        </li>
                        <li class="nav-item  w-50" role="presentation">
                            <button class="nav-link w-100" id="profile-tab2" data-bs-toggle="tab"
                                    data-bs-target="#profile2" type="button" role="tab">
                                نظرات
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="underlineTabsContent">
                        <!-- Course Content Tab -->
                        <div class="tab-pane fade show active" id="home2" role="tabpanel">
                            @php($lessons = $course->lessons)
                        </div>

                        <!-- Comments Tab -->
                        <div class="tab-pane fade" id="profile2" role="tabpanel">
                            <h4>نظرات</h4>
                            <div class="comments-list mb-4">
                                @if(count($comments) > 0)
                                    @foreach($comments as $comment)
                                        <div class="comment-item mb-3 p-3 shadow-sm">
                                            <div
                                                class="comment-header d-flex justify-content-between align-items-center">
                                                <strong>{{ $comment->user->name }}</strong>
                                                <div class="comment-rating mt-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $comment->rate)
                                                            <span class="star rated"><i class="fa fa-star "></i> </span>
                                                        @else
                                                            <span class="star"><i class="fa fa-star "></i></span>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="comment-body mt-2">
                                                {{ $comment->content }}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">هنوز نظری ثبت نشده است.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-5 shadow course-box p-3">
                <div class="col-12 d-flex align-items-center flex-column">
                    <div class="d-flex flex-column flex-sm-row align-items-center w-100">
                        <img src="{{ url($course->teacher->photo??'') }}"
                             class="img-fluid course-teacher-profile rounded-circle teacher-img">
                        <div class="ms-0 me-2 ms-sm-3 text-center text-sm-start text-md-end">
                            <div class="text-muted fw-light">مدرس دوره</div>
                            <div class="fw-bold">{{ $course->teacher->fullname ?? '' }}</div>
                            @if(!is_null($course->teacher))
                                <div
                                    class="text-muted fw-light teacher-bio">{{ Str::limit($course->teacher->bio, 100, '...') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="col-12 text-center text-sm-start mt-3">
                        <a href="#" class="btn btn-primary teacher-selection w-sm-auto">مشاهده استاد</a>
                    </div>
                </div>
                <div class="normal-divider"></div>
                <div class="col-12 d-flex align-items-center flex-column">
                    <div class="popular-course bg-secondary w-100">
                    <div class="text-white d-flex p-2 align-items-center" style="background-color: #0d47a1;">
                        <i class="fa fa-heart"></i>
                        <span class="me-2">محبوبترین پکیج ها</span>
                    </div>

                    </div>
                    {{-- <div class="col-12 popular-course-items mt-4">
                        @foreach($popularCourses as $popularCourse)
                            <div class="col-12 d-flex justify-content-between">
                                <div class="d-flex">
                                    <img src="{{asset('storage/' . $popularCourse->image)}}"
                                         class="img-fluid popular-course-image"/>
                                    <div class="me-2">
                                        <div class="popular-course-title">{{$popularCourse->title}}</div>
                                        <div class="popular-course-description text-black-50">{{Str::limit($popularCourse->description,50,'...')}}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="popular-course-rating">{{$popularCourse->rating}}</div>
                                    <i class="fas fa-star filled-star"></i>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="border-popular-posts"></div>
                            @endif
                        @endforeach
                    </div> --}}
                </div>


            </div>


        </div>

        <div class="normal-divider"></div>

        <!-- Comment Form and Rating Section -->
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 shadow course-box p-3">
                    <div class="row">
                        <!-- Comment Form -->
                        <div class="col-md-6 col-12 d-flex flex-column justify-content-between">
                            <div>
                                <h5>چطور می‌توانم در مورد این پکیج نظر بدهم؟</h5>
                                <p class="text-muted comment-subtitle">لطفاً تجربیات و پیشنهادات خود را در مورد خدمات ما
                                    بیان کنید تا بتوانیم بهبود یافته و بهتر خدمت‌رسانی کنیم.</p>
                            </div>
                            <div class="comment-form mt-4">
                                <form action="{{ route('frontend.courses.show', $course->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="content" class="form-control" rows="4"
                                                  placeholder="نظر خود را بنویسید..." required></textarea>
                                    </div>
                                    <div class="form-group mt-3">
                                        <div class="star-rating">
                                            <i class="fas fa-star" data-value="5"></i>
                                            <i class="fas fa-star" data-value="4"></i>
                                            <i class="fas fa-star" data-value="3"></i>
                                            <i class="fas fa-star" data-value="2"></i>
                                            <i class="fas fa-star" data-value="1"></i>
                                        </div>
                                        <input type="hidden" name="rate" id="ratingInputComment" value="0">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" class="btn btn-primary my-3">
                                            افزودن نظر جدید<i class="fa me-2 fa-arrow-left"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- <!-- Rating Breakdown -->
                        <div class="col-md-6 col-12">
                            <div class="form-group ">
                                <div class="d-flex mb-3 align-items-center">
                                    <label class="rating-label"
                                           for="rating">{{ \App\Models\Comment::getAverageRatingForCourse($course->id) }}</label>
                                    <div class="star-rating">
                                        <div class="stars-outer">
                                            <div class="stars-inner" id="starsInner"></div>
                                            <div id="starsInner"
                                                 data-rating="{{ \App\Models\Comment::getAverageRatingForCourse($course->id) }}"></div>
                                            <input type="hidden" id="ratingInput">
                                        </div>
                                    </div>
                                    <span class="text-muted mx-2">({{ count($comments) }} نظر)</span>
                                    <input type="hidden" name="rate" id="ratingInput" value="">
                                </div>

                                <div class="col-md-12 mx-auto">
                                    @foreach ($ratingsPercentage as $star => $percentage)
                                        <div class="rating-bar d-flex align-items-center mb-2">
                                            <span class="mx-2">{{ $star }} ستاره</span>
                                            <div class="progress flex-grow-1" style="height: 7px; position: relative;">
                                                <div
                                                    class="progress-bar {{ $percentage == $maxPercentage ? 'bg-secondary' : 'bg-fourth-color' }}"
                                                    style="width: {{ $percentage }}%"></div>
                                                <span class="star-percentage"
                                                      style="position: absolute; left: 3%; font-size: 7px; transform: translateX(-50%); color: @if($percentage > 90) #fff @else #000 @endif;">
                                                    {{ number_format($percentage) }}%
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            // Script for handling star rating selection
            document.addEventListener("DOMContentLoaded", function () {
                const stars = document.querySelectorAll("#starRating i");
                const ratingInput = document.getElementById("ratingInput");

                stars.forEach(star => {
                    star.addEventListener("click", function () {
                        const value = this.getAttribute("data-value");
                        ratingInput.value = value;

                        // Reset all stars
                        stars.forEach(s => s.classList.remove("selected"));

                        // Highlight selected stars
                        for (let i = 0; i < value; i++) {
                            stars[i].classList.add("selected");
                        }
                    });
                });
            });

            // Script for displaying average rating
            document.addEventListener("DOMContentLoaded", function () {
                const averageRating = parseFloat("{{ $averageRating }}");
                const starsInner = document.getElementById('starsInner');
                const ratingInput = document.getElementById('ratingInput');

                // Maximum number of stars
                const starTotal = 5;

                // Convert average rating to percentage
                const starPercentage = (averageRating / starTotal) * 100;

                // Apply percentage to the width of filled stars
                starsInner.style.width = `${starPercentage}%`;

                // Store the rating value in the hidden input
                ratingInput.value = averageRating.toFixed(1);
            });

            // Script for handling star rating hover and selection in the comment form
            document.addEventListener("DOMContentLoaded", function () {
                const stars = document.querySelectorAll(".star-rating i");
                const ratingInput = document.getElementById("ratingInputComment");

                stars.forEach(star => {
                    // Hover effect to change the color of stars
                    star.addEventListener("mouseenter", function () {
                        const currentValue = this.getAttribute("data-value");
                        stars.forEach(s => {
                            if (s.getAttribute("data-value") <= currentValue) {
                                s.style.color = "#ffcc00"; // Gold color for hover
                            } else {
                                s.style.color = "#ccc"; // Gray color for non-hovered stars
                            }
                        });
                    });

                    // Reset color after mouse leaves
                    star.addEventListener("mouseleave", function () {
                        stars.forEach(s => {
                            s.style.color = s.classList.contains('selected') ? "#ffcc00" : "#ccc";
                        });
                    });

                    // Set rating value on click
                    star.addEventListener("click", function () {
                        let ratingValue = this.getAttribute("data-value");
                        ratingInput.value = ratingValue; // Update the hidden input value

                        // Add 'selected' class to highlight the selected stars
                        stars.forEach(s => s.classList.remove("selected"));
                        for (let i = 0; i < ratingValue; i++) {
                            stars[i].classList.add("selected");
                        }
                    });
                });
            });

            $(document).ready(function () {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                $('#addToCart').click(function () {
                    let button = $(this);
                    let courseId = '{{$course->id}}';

                    // Change Button UI

                    $('#cartText').text('در حال افزودن...');
                    $('#cartLoader').removeClass('d-none');

                    $.ajax({
                        url: '{{route('frontend.card.show')}}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            course_id: courseId,
                            quantity: 1
                        },
                        success: function (response) {
                            if (response.success) {
                                button.prop('disabled', true);
                                Toast.fire({
                                    icon: "success",
                                    title: "محصول با موفقیت به سبد خرید اضافه شد!"
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    title: response.message
                                });

                            }
                        },
                        error: function () {
                            Toast.fire({
                                icon: "error",
                                title: response.message
                            });

                        },
                        complete: function () {
                            // Reset Button UI
                            button.prop('disabled', false);
                            $('#cartText').text('افزودن به سبد خرید');
                            $('#cartLoader').addClass('d-none');
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection
