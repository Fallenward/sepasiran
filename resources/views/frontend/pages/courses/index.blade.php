@extends('frontend.layouts.app')

@section('title', $title)

@section('content')


<div class="container">
    <x-section-header title="لیست دوره ها" buttonText="" link="#"/>
</div>

<div class="container mt-5">
    <!-- Mobile filter button -->
    <button id="course-filterButton" class="d-md-none btn btn-secondary course-fixed-bottom" data-toggle="tooltip" data-placement="top" title="فیلترها">
        فیلترها <i class="fas fa-filter"></i>
    </button>

    <!-- Filters section -->
    <div id="course-filters" class="row">
        <div id="filter-status" class="col-12 mb-2">
            <div class="alert alert-primary p-2 m-0" role="alert">
                <p class="mb-0" id="filter-status-text">در حال بارگذاری...</p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <x-field-text
                id="searchItem"
                label="جستجو"
                placeholder="نام درس، نام استاد، دسته بندی ..."
                value="">
            </x-field-text>
        </div>

        <div class="col-6 col-md-4">
            <x-field-select2 :fields="$levels" label="پایه تحصیلی" id="level_id" :multiple="true"/>
        </div>

        <div class="col-6 col-md-4">
            <x-field-select2 :fields="$categories" label="دسته بندی" id="category_id" :multiple="true"/>
        </div>

        <div class="col-6 col-md-4">
            <x-field-select2 :fields="$teachers" label="استاد" id="user_id" :multiple="true"/>
        </div>

        <div class="col-6 col-md-4">
            <x-field-select2 :fields="$paymentTypes" label="قیمت" id="price" :multiple="true"/>
        </div>
    </div>
</div>

<div class="normal-divider"></div>

<!-- Loading spinner -->
<div id="loadingSpinner" class="text-center my-4 container" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2 text-center">در حال بارگذاری...</p>
</div>

<!-- No results alert -->
<div id="noResultsAlert" class="alert alert-warning text-center my-4" style="display: none;">
    موردی یافت نشد!
</div>

<!-- Courses container -->
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

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    function fetchFilteredCourses(page = 1) {
        $('#loadingSpinner').show();
        $('#filter-status-text').text('در حال بارگذاری...');
        $('#noResultsAlert').hide();

        var level_id = $('#level_id').val();
        var category_id = $('#category_id').val();
        var user_id = $('#user_id').val();
        var price = $('#price').val();
        var searchItem = $('#searchItem').val();

        $.ajax({
            url: '{{ route('frontend.courses.filter') }}',
            method: 'GET',
            data: {
                level_id: level_id,
                category_id: category_id,
                user_id: user_id,
                price: price,
                searchItem: searchItem,
                page: page
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                $('#courses-container').html(response);

                var resultCount = $('#courses-container .course-item').length;

                if (resultCount === 0) {
                    $('#filter-status-text').text('هیچ موردی یافت نشد.');
                    $('#noResultsAlert').show();
                } else {
                    $('#filter-status-text').text(resultCount + ' مورد یافت شد.');
                    $('#noResultsAlert').hide();
                }
            },
            complete: function () {
                $('#loadingSpinner').hide();
            },
            error: function () {
                $('#courses-container').html('<p class="text-danger text-center">خطا در بارگذاری داده‌ها.</p>');
                $('#filter-status-text').text('خطا در بارگذاری داده‌ها.');
                $('#loadingSpinner').hide();
            }
        });
    }

    // Initial load
    fetchFilteredCourses();

    // Filters change event
    $('#level_id, #category_id, #user_id, #price, #searchItem').on('change input', function () {
        fetchFilteredCourses();
    });

    // Pagination links event (delegated)
    $(document).on('click', '#courses-container .pagination a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = url.split('page=')[1];
        if (page) {
            fetchFilteredCourses(page);
            // Scroll to top of courses on page change
            $('html, body').animate({ scrollTop: $('#courses-container').offset().top - 100 }, 500);
        }
    });

    // Mobile filter sidebar toggle
    const filterButton = document.getElementById("course-filterButton");
    const filtersSection = document.getElementById("course-filters");
    const overlay = document.createElement("div");
    overlay.classList.add("course-overlay");
    document.body.appendChild(overlay);

    filterButton.addEventListener("click", function() {
        filtersSection.classList.add("open");
        overlay.classList.add("active");
    });

    overlay.addEventListener("click", function() {
        filtersSection.classList.remove("open");
        overlay.classList.remove("active");
    });
});
</script>
@endsection
