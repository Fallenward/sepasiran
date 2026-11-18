@props(['items', 'id', 'slidesPerView', 'type', 'ride', 'enabledDesc', 'enabledLink', 'viewLink'])

@php
    use Carbon\Carbon;
    use Morilog\Jalali\Jalalian;
    use App\Helpers\GeneralHelpers;

    // Ensure $items is a collection
    if (! $items instanceof \Illuminate\Support\Collection) {
        $items = collect($items);
    }
@endphp

<style>
    .img-rounded { border-radius: 1rem; object-fit: cover; }
    .overflow-hidden-rounded { border-radius: 1rem; overflow: hidden; }
    .img-fixed-size { width: 100%; height: 100%; object-fit: cover; }

    @media (min-width: 992px) {
        .feature-size { width: 498px; height: 498px; }
        .horizontal-card-size { position: relative; width: 498px; height: 271px; }
        .horizontal-img { width: 236px; height: 100%; }
        .horizontal-card-size .text-panel {
            position: relative;
            margin-right: -100px;
            z-index: 2;
            width: calc(100% + 100px);
        }
    }

    @media (max-width: 991.98px) {
        .horizontal-card-size { width: 90%; }
        .horizontal-img { width: 100%; height: 200px; border-radius: 1rem 1rem 0 0 !important; }
        .horizontal-card-size .text-panel { margin-left: 0; width: 100%; }
    }
</style>

<div class="d-flex flex-wrap justify-content-center gap-3 pt-5">
    @if($items->isNotEmpty())
        <!-- Feature Blog (First one) -->
        <div class="position-relative overflow-hidden-rounded feature-size">
            <img src="{{ asset($items[0]->image) }}" class="w-100 h-90 img-rounded" alt="{{ $items[0]->title }}">
            <div
                class="position-absolute bottom-0 start-50 translate-middle-x w-75 mt-4"
                onclick="window.location.href='{{ route($viewLink, ['slug' => $items[0]->slug]) }}'"
                style="cursor:pointer; z-index: 2;"
            >
                <div class="bg-white rounded shadow-lg p-3 border-bottom border-4 border-primary d-flex flex-column justify-content-between">
                    @php
                        $persianDate = Jalalian::fromCarbon(Carbon::parse($items[0]->created_at))->format('j F Y');
                    @endphp
                    <div class="text-muted small">{{ $persianDate }} <i class="fa fa-calendar-alt ms-1"></i></div>
                    <div class="fw-bold fs-5">{{ $items[0]->title }}</div>
                    @if($enabledDesc)
                        <div class="text-muted small">{{ GeneralHelpers::limitTextToTwoLines($items[0]->description) }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Side Blog Cards -->
        <div class="d-flex flex-column gap-3">
            @foreach($items->slice(1, 2) as $item)
                <div class="overflow-hidden-rounded horizontal-card-size d-flex flex-lg-row flex-column">
                    <img src="{{ asset($item->image) }}" class="img-rounded horizontal-img" alt="{{ $item->title }}">

                    <div
                        class="text-panel flex-grow-1 p-3 d-flex align-items-center"
                        onclick="window.location.href='{{ route($viewLink, ['slug' => $item->slug]) }}'"
                        style="cursor: pointer;"
                    >
                        <div class="bg-white rounded shadow-lg p-3 border-bottom border-4 border-primary w-100">
                            @php
                                $persianDate = Jalalian::fromCarbon(Carbon::parse($item->created_at))->format('j F Y');
                            @endphp
                            <div class="text-muted small">{{ $persianDate }} <i class="fa fa-calendar-alt"></i></div>
                            <div class="fw-bold fs-5">{{ $item->title }}</div>
                            @if($enabledDesc)
                                <div class="text-muted small">{{ GeneralHelpers::limitTextToTwoLines($item->description) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted">مطلبی برای نمایش وجود ندارد.</p>
    @endif
</div>
 