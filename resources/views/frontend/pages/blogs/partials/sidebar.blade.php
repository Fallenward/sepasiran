@php use Carbon\Carbon;use Morilog\Jalali\Jalalian; @endphp
<div class="card mb-4 animate__animated animate__fadeInRight blog-box shadow bg-secondary">
    <div class="card-body">
        <form>
            <div style="position: relative;">
                <input type="text" class="form-control" placeholder="جستجو..." style="padding-right: 35px;">
                <i class="fas fa-search"
                   style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: gray;"></i>
            </div>
        </form>
    </div>
</div>


<div class="card mb-4 animate__animated animate__fadeInRight blog-box shadow">
    <div class="card-body p-0">
        <h5 class="px-4 pt-3 card-title">
            <i class="fa fa-heart"></i> آخرین پست ها
        </h5>
        <div class="border-bottom-sidebar"></div>
        <ul class="list-group p-0 border-0">
            @foreach($latestPosts as $post)
                @php
                    $carbonDate = Carbon::parse($post->created_at);
                    $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                @endphp
                <a href="{{route('frontend.blogs.show',$post->slug)}}" class="text-decoration-none">
                    <li class="list-group-item d-flex align-items-start border-bottom border-top-0">
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="rounded"
                             style="width: 60px; height: 60px; object-fit: cover; margin-left: 10px;">
                        <div>
                            <h6 class="mb-1">{{ $post->title }}</h6>
                            <small class="text-muted">{{ $persianDate }}</small>

                        </div>
                    </li>
                </a>
            @endforeach
        </ul>
    </div>
</div>

<div class="card mb-4 animate__animated animate__fadeInRight blog-box shadow">
    <div class="card-body p-0">
        <h5 class="px-4 pt-3 card-title">
            <i class="fa fa-list"></i> دسته بندی ها
        </h5>
        <div class="border-bottom-sidebar"></div>
        <ul class="list-group p-0 border-0">
            @foreach($categories as $category)
                <a target="_blank" href="{{ route('frontend.blogs.show.category', $category->slug) }}" class="text-decoration-none">
                    <li class="list-group-item d-flex align-items-center border-bottom border-top-0">
                        <i class="fas fa-folder text-secondary" style="margin-left: 10px;"></i>
                        <span>{{ $category->name }}</span>
                    </li>
                </a>
            @endforeach
        </ul>
    </div>
</div>


