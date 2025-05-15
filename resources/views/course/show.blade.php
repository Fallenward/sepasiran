@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container py-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="bg-white shadow-sm rounded p-3 text-center">
        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="img-fluid rounded mb-3" alt="{{ $course->title }}">
        <h6>{{ $course->title }}</h6>
        <p class="text-primary fw-bold">هزینه دوره: {{ number_format($course->discount_price ?? $course->price) }} تومان</p>
        <button class="btn btn-primary w-100">افزودن به سبد خرید</button>
      </div>

      <!-- Popular Courses -->
      <div class="bg-white shadow-sm rounded p-3 mt-4">
        <h6 class="bg-primary text-white p-2 rounded">محبوب‌ترین دوره‌ها</h6>
        <ul class="list-unstyled">
          @foreach($popularCourses as $item)
          <li class="d-flex gap-2 mb-3">
            <img src="{{ asset('storage/' . $item->thumbnail) }}" width="50" class="rounded" alt="">
            <div>
              <a href="{{ route('courses.show', $item->slug) }}" class="text-decoration-none">
                <p class="mb-0">{{ $item->title }}</p>
              </a>
              <small class="text-muted">{{ Str::limit($item->short_description, 40) }}</small>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
      <div class="bg-white shadow-sm rounded p-4">
        <h5 class="mb-2">{{ $course->title }}</h5>
        <p>{{ $course->description }}</p>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="courseTabs">
          <li class="nav-item"><a class="nav-link active" href="#">مرور کلی</a></li>
          <li class="nav-item"><a class="nav-link" href="#">سوالات و پرسش</a></li>
          <li class="nav-item"><a class="nav-link" href="#">محتوا</a></li>
        </ul>

        <!-- Video -->
        @if($course->video_demo_url)
        <div class="mb-4 text-center">
          <div class="ratio ratio-16x9 border rounded">
            <iframe src="{{ $course->video_demo_url }}" title="ویدیو معرفی" allowfullscreen></iframe>
          </div>
        </div>
        @endif

        <!-- Curriculum -->
        <h6 class="mb-3">سرفصل‌ها</h6>
        <div class="accordion mb-4" id="curriculumAccordion">
          @foreach($course->curriculum as $index => $lesson)
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                  {{ $lesson->title }}
                </button>
              </h2>
              <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#curriculumAccordion">
                <div class="accordion-body">
                  {{ $lesson->content }}
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Reviews -->
        <h6 class="mb-3">نظرات</h6>
        @foreach($course->reviews as $review)
          <div class="mb-3 d-flex gap-3">
            <img src="{{ asset('images/user.png') }}" width="40" class="rounded-circle" alt="User">
            <div>
              <strong>{{ $review->user->name }}</strong>
              <p class="mb-1 text-muted small">{{ $review->rating }}</p>
              <p>{{ $review->comment }}</p>
            </div>
          </div>
        @endforeach
        <p class="text-center"><a href="#">مشاهده بیشتر</a></p>

        <!-- Rating Summary -->
        <div class="bg-light rounded p-4 mt-4">
          <h6>چطور می‌توانم در مورد این دوره نظر بدهم؟</h6>
          <p class="text-muted small">اگر در این دوره ثبت‌نام کرده‌اید...</p>
          <a href="#" class="btn btn-primary">افزودن نظر جدید</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
