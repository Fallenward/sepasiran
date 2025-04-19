@php use Carbon\Carbon;use Morilog\Jalali\Jalalian; @endphp
<div class="card mb-4 animate__animated animate__fadeIn blog-box shadow">
    <img src="{{ asset($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-md-baseline flex-column flex-md-row">
            <h1 class="card-title">{{ $blog->title }}</h1>
            @php
                $carbonDate = Carbon::parse($blog->created_at);
                $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
            @endphp
            <div class="d-flex align-items-center mb-4">
                <div class="text-muted"><i class="fa fa-calendar-alt"></i> {{ $persianDate }}</div>


            </div>
        </div>
        <p class="card-text">{!! $blog->content !!}</p>
    </div>
</div>

<div class="rating-section text-center animate__animated animate__fadeInUp">

    @php
        $fullStars = floor($blog->rating); // تعداد ستاره‌های کاملاً پر
        $decimalPart = $blog->rating - $fullStars; // بخش اعشاری امتیاز
        $halfStar = ($decimalPart >= 0.5) ? 1 : 0; // بررسی نیم‌ستاره
        $emptyStars = 5 - ($fullStars + $halfStar); // تعداد ستاره‌های خالی

    @endphp

    <div class="star-rating">
        {{-- ستاره‌های کاملاً پر --}}
        @for ($i = 0; $i < $fullStars; $i++)
            <span class="fa fa-star filled"></span>
        @endfor

        {{-- نیمه‌ستاره در صورتی که مقدار اعشاری 0.5 یا بیشتر باشد --}}
        @if ($halfStar)
            <span class="fa fa-star-half "></span>
        @endif

        {{-- ستاره‌های خالی --}}
        @for ($i = 0; $i < $emptyStars; $i++)
            <span class="fa fa-star not-filled"></span>
        @endfor



    </div>
    <div>
        <span class="rating-number card-text mx-2">{{ number_format($blog->rating, 1) }}</span>
        <span class="comment-count">از {{$blogRatingCount}} رای</span>
    </div>
</div>

{{-- بخش نمایش نظرات کاربران --}}
<div class="comments-section mt-5">
    <h3>نظرات کاربران</h3>

    @if($blog->comments->count() > 0)
        <div class="list-group comments-area">
            @foreach($blog->comments as $comment)
                @if($comment->status==\App\Models\Comment::STATUS_APPROVED)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">{{ $comment->name }}</h5>
                            <small
                                class="text-muted">{{ Jalalian::fromCarbon(Carbon::parse($comment->created_at))->format('j F Y') }}</small>
                        </div>
                        <p class="mb-1">{{ $comment->content }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <p class="text-muted">هنوز نظری ثبت نشده است. اولین نفر باشید!</p>
    @endif
</div>
