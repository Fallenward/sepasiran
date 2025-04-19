@extends('frontend.layouts.app')

@section('title', $title)

@section('content')
    <!-- Section 1: Header -->
    <div class="container">
        <div class="col-12">
            {{ Breadcrumbs::render('blogs.show', $blog) }}
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 col-md-7">
                @include('frontend.pages.blogs.partials.main-content', ['blog' => $blog])
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-md-5 sidebar">
                @include('frontend.pages.blogs.partials.sidebar', [
                    'latestPosts' => $latestPosts,
                    'categories' => $categories
                ])
            </div>
        </div>

        <!-- Comments Section -->
        <div class="row mt-4">
            <div class="col-12">
                @include('frontend.pages.blogs.partials.comments-section')
            </div>
        </div>
    </div>

    <!-- Sticky Button for Mobile -->
    <button class="btn btn-primary sticky-button" onclick="toggleSidebar()">☰</button>
@endsection

@section('scripts')
    <script type="text/javascript">
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }

        document.addEventListener("DOMContentLoaded", function () {
            const stars = document.querySelectorAll(".star-rating .fa");
            const ratingDisplay = document.querySelector(".rating-number"); // نمایش مقدار امتیاز
            const totalRatings = 37; // تعداد کل امتیازات (می‌توان از دیتابیس دریافت کرد)
            let currentRating = parseFloat(ratingDisplay.textContent); // مقدار فعلی امتیاز
            let starRatedResponse = document.querySelector(".star-rated-response"); // مقدار فعلی امتیاز

            function updateStars(rating) {
                stars.forEach((star, index) => {
                    star.classList.remove("filled", "half-filled"); // حذف کلاس‌های قبلی
                    star.style.color = ""; // بازنشانی رنگ دستی

                    if (index < Math.floor(rating)) {
                        star.classList.add("filled"); // پر کردن کامل ستاره
                    } else if (index < Math.ceil(rating) && rating % 1 !== 0) {
                        star.classList.add("half-filled"); // نیمه پر کردن
                    }
                });
            }


            // نمایش مقدار پیش‌فرض ستاره‌ها
            updateStars(currentRating);

            // هنگام هاور، تغییر مقدار ستاره‌ها
            stars.forEach((star, index) => {
                star.addEventListener("mouseover", () => {
                    updateStars(index + 1);
                });

                star.addEventListener("mouseout", () => {
                    updateStars(currentRating);
                });

                star.addEventListener("click", () => {
                    const rating = index + 1;
                    submitRating('{{$blog->id}}', rating);
                });
            });
        });

        function submitRating(blogId, rating) {
            fetch(`/blog/${blogId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ rating: rating })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // نمایش پیام موفقیت‌آمیز با SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        // به‌روزرسانی نمایش امتیاز
                        const ratingDisplay = document.querySelector(".rating-number");
                        if (ratingDisplay && data.average_rating) {
                            ratingDisplay.textContent = parseFloat(data.average_rating).toFixed(1);
                        }
                    } else if (data.error) {
                        // نمایش پیام خطا با SweetAlert2
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.error,
                            showConfirmButton: true
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while submitting your rating.',
                        showConfirmButton: true
                    });
                });
        }
    </script>
@endsection
