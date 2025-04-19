@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <section class="Slider-section">
        <div class="col-12">
            <x-swiper :id="'carouselSliderIndicators'" :items="$banners" :type="'image-only'" :slidesPerView="1"
                      :ride="false"/>
        </div>
    </section>

    <div class="container">
        <x-section-header title="لیست دوره ها" buttonText="" link="#"/>
    </div>

    <div class="container mt-5">
        <!-- Button to open the sidebar on mobile -->
        <button id="course-filterButton" class="d-md-none btn btn-secondary course-fixed-bottom" data-toggle="tooltip" data-placement="top" title="">
            فیلترها<i class="fas fa-filter"></i>
        </button>



        <!-- Filters section (shared for both mobile and desktop) -->
        <div id="course-filters" class="row">
            <!-- Filter Status Section -->
            <div id="filter-status">
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
                <x-field-select2 :fields="$fields" label="رشته تحصیلی" id="field_id" :multiple="true"/>
            </div>
            <div class="col-6 col-md-4">
                <x-field-select2 :fields="$teachers" label="استاد" id="user_id" :multiple="true"/>
            </div>
            <div class="col-6 col-md-4">
                <x-field-select2 :fields="$categories" label="دسته بندی" id="category_id" :multiple="true"/>
            </div>
            <div class="col-6 col-md-4">
                <x-field-select2 :fields="$paymentTypes" label="قیمت" id="price" :multiple="true"/>
            </div>
        </div>
    </div>

    <div class="normal-divider"></div>


    <!-- Loading Indicator -->
    <div id="loadingSpinner" class="text-center my-4 container" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-center">در حال بارگذاری...</p>
    </div>

    <!-- Alert for No Results -->
    <div id="noResultsAlert" class="alert alert-warning text-center my-4" style="display: none;">
        موردی یافت نشد!
    </div>

    <!-- Courses Container -->
    <div class="container" id="courses-container">
        <!-- Filtered Courses will be loaded here -->
    </div>

    @section('scripts')
        <script type="text/javascript">

            $(document).ready(function () {
                // Get CSRF token from the meta tag
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Function to fetch filtered courses
                function fetchFilteredCourses() {
                    // Show loading spinner
                    $('#loadingSpinner').show();
                    $('#filter-status-text').text('در حال بارگذاری...'); // Update filter status text

                    // Hide no results alert initially
                    $('#noResultsAlert').hide();

                    var level_id = $('#level_id').val();
                    var field_id = $('#field_id').val();
                    var course_id = $('#course_id').val();
                    var user_id = $('#user_id').val();
                    var category_id = $('#category_id').val();
                    var price = $('#price').val();
                    var searchItem = $('#searchItem').val();

                    $.ajax({
                        url: '{{ route('frontend.courses.filter') }}',
                        method: 'GET',
                        data: {
                            level_id: level_id,
                            field_id: field_id,
                            course_id: course_id,
                            user_id: user_id,
                            category_id: category_id,
                            price: price,
                            searchItem: searchItem,
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken  // Include CSRF token in headers
                        },
                        success: function (response) {
                            // Update the course list container with the filtered courses

                            $('#courses-container').html(response);


                            // Update filter status text
                            var resultCount = $('#courses-container>.row').children().length;

                            if (resultCount === 0) {
                                $('#filter-status-text').text('هیچ موردی یافت نشد.');
                                $('#noResultsAlert').show();
                            } else {
                                $('#filter-status-text').text(resultCount + ' مورد یافت شد.');
                                $('#noResultsAlert').hide();
                            }
                        },
                        complete: function () {
                            // Hide loading spinner after request is complete
                            $('#loadingSpinner').hide();
                        },
                        error: function () {
                            // Handle errors if needed
                            $('#courses-container').html('<p class="text-danger text-center">خطا در بارگذاری داده‌ها.</p>');
                            $('#filter-status-text').text('خطا در بارگذاری داده‌ها.');
                            $('#loadingSpinner').hide();
                        }
                    });
                }

                // Initial load of courses
                fetchFilteredCourses();

                // Listen for changes in the select fields
                $('#level_id, #field_id, #course_id, #user_id, #category_id, #price, #searchItem').on('change input', function () {
                    fetchFilteredCourses();
                });

                const filterButton = document.getElementById("course-filterButton");
                const filtersSection = document.getElementById("course-filters");
                const overlay = document.createElement("div");
                overlay.classList.add("course-overlay");

                // Append overlay to the body
                document.body.appendChild(overlay);

                // Open sidebar on mobile
                filterButton.addEventListener("click", function() {
                    filtersSection.classList.add("open");
                    overlay.classList.add("active");
                });

                // Close sidebar on mobile
                overlay.addEventListener("click", function() {
                    filtersSection.classList.remove("open");
                    overlay.classList.remove("active");
                });
            });
        </script>
    @endsection
@endsection
