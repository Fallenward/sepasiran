
@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GenralHelpers;
@endphpF

<div class="container">
    <!-- First Row: 1 card taking up 6 columns -->R
    <div class="row">
        <div class="col-md-6">
            @if($items->isNotEmpty())
                <div class="card">
                    @if($items[0]->image)
                        <img src="{{ asset($items[0]->image) }}" class="card-img-top feature-image-blog" alt="{{ $items[0]->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $items[0]->title }}</h5>
                        @if($enabledDesc)
                            <p class="card-text">{{ GenralHelpers::limitTextToTwoLines($items[0]->description) }}</p>
                        @endif
                        @if($enabledLink)
                            <div class="col-12 justify-content-between d-flex align-items-baseline">
                                @php
                                    $carbonDate = Carbon::parse($items[0]->created_at);
                                    $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                @endphp
                                <div>
                                    <span>{{ $persianDate }} <i class="fa fa-calendar-alt"></i></span>
                                </div>
                                @if($enabledLink)
                                    <a href="{{ route($viewLink, $items[0]->slug) }}" class="btn btn-primary">مشاهده</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Second Row: 2 cards, each taking up 6 columns -->
    <div class="row">
        @foreach($items->slice(1, 2) as $item) <!-- Slice to get the next 2 items -->
            <div class="col-md-6">
                <div class="card">
                    @if($item->image)
                        <img src="{{ asset($item->image) }}" class="card-img-top feature-image-blog" alt="{{ $item->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        @if($enabledDesc)
                            <p class="card-text">{{ GenralHelpers::limitTextToTwoLines($item->description) }}</p>
                        @endif
                        @if($enabledLink)
                            <div class="col-12 justify-content-between d-flex align-items-baseline">
                                @php
                                    $carbonDate = Carbon::parse($item->created_at);
                                    $persianDate = Jalalian::fromCarbon($carbonDate)->format('j F Y');
                                @endphp
                                <div>
                                    <span>{{ $persianDate }} <i class="fa fa-calendar-alt"></i></span>
                                </div>
                                @if($enabledLink)
                                    <a href="{{ route($viewLink, $item->slug) }}" class="btn btn-primary">مشاهده</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12 text-center">
            {{ $items->links() }} <!-- Pagination links -->
        </div>
    </div>
</div>
