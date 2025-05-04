@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphp

<div class="container my-4">
    @if($items->count() >= 1)
        <div class="row">
            <!-- Left Side: Large Feature Card -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    @if($items[0]->image)
                        <img src="{{ asset($items[0]->image) }}" class="card-img-top feature-image-blog" alt="{{ $items[0]->title }}">
                    @endif
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <h5 class="card-title">{{ $items[0]->title }}</h5>
                        @if($enabledDesc)
                            <p class="card-text">{{ GenralHelpers::limitTextToTwoLines($items[0]->description) }}</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            @php
                                $carbonDate = Carbon::parse($items[0]->created_at);
                                $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                            @endphp
                            <small>{{ $persianDate }} <i class="fa fa-calendar-alt"></i></small>
                            @if($enabledLink)
                                <a href="{{ route($viewLink, $items[0]->slug) }}" class="btn btn-sm btn-primary">مشاهده</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Two Smaller Cards -->
            <div class="col-md-6">
                @foreach($items->slice(1, 2) as $item)
                    <div class="card mb-4">
                        @if($item->image)
                            <img src="{{ asset($item->image) }}" class="card-img-top feature-image-blog" alt="{{ $item->title }}">
                        @endif
                        <div class="card-body d-flex flex-column justify-content-between h-100">
                            <h6 class="card-title">{{ $item->title }}</h6>
                            @if($enabledDesc)
                                <p class="card-text small">{{ GenralHelpers::limitTextToTwoLines($item->description) }}</p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                @php
                                    $carbonDate = Carbon::parse($item->created_at);
                                    $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                @endphp
                                <small>{{ $persianDate }} <i class="fa fa-calendar-alt"></i></small>
                                @if($enabledLink)
                                    <a href="{{ route($viewLink, $item->slug) }}" class="btn btn-sm btn-outline-primary">مشاهده</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Optional Pagination -->
    @if($items->count() > 3)
        <div class="row">
            <div class="col-12 text-center">
                {{ $items->links() }}
            </div>
        </div>
    @endif
</div>
