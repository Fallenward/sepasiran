@props([
    'filters' => [],
    'action' => request()->url(),
    'suggestUrl' => null,
    'searchable' => true,
    'searchPlaceholder' => 'نام، نژاد یا شهر را بنویسید…',
    'searchIcon' => 'dogs',
])

@php
    $labelMap = [
        'city' => 'شهر',
        'breed' => 'نژاد',
        'specialty' => 'تخصص',
        'size' => 'اندازه',
        'duration' => 'مدت زمان',
        'category' => 'دسته‌بندی',
        'level' => 'سطح',
        'brand' => 'برند',
        'gender' => 'جنسیت',
        'age' => 'سن',
        'price' => 'قیمت',
        'featured' => 'وضعیت',
    ];

    $sizeLabels = [
        'small' => 'کوچک',
        'medium' => 'متوسط',
        'large' => 'بزرگ',
    ];

    // keep optional suggestUrl
    $q = request('q');
    if ($searchPlaceholder === 'نام، نژاد یا شهر را بنویسید…' && request()->routeIs('frontend.online.courses', 'frontend.courses.*')) {
        $searchPlaceholder = 'عنوان یا دسته را بنویسید';
        $searchIcon = 'course';
    }
    if ($searchPlaceholder === 'نام، نژاد یا شهر را بنویسید…' && request()->routeIs('frontend.clinic.insurance.*')) {
        $searchPlaceholder = 'نام بیمه را بنویسید';
        $searchIcon = 'insurance';
    }

    $active = [];
    if (filled($q)) {
        $active[] = [
            'key' => 'q',
            'value' => $q,
            'label' => 'جستجو: ' . $q,
            'is_query' => true,
        ];
    }

    foreach ($filters as $filterKey => $items) {
        $items = collect($items);
        $selected = collect(request($filterKey, []))->map(fn ($v) => (string) $v);
        foreach ($items as $item) {
            $value = is_object($item) ? (string) ($item->id ?? $item->value ?? '') : (string) (is_array($item) ? ($item['id'] ?? '') : $item);
            $name = is_object($item)
                ? ($item->name ?? $value)
                : (is_array($item) ? ($item['name'] ?? $value) : ($sizeLabels[$value] ?? ucfirst($value)));
            if ($value !== '' && $selected->contains($value)) {
                $active[] = [
                    'key' => $filterKey,
                    'value' => $value,
                    'label' => ($labelMap[$filterKey] ?? $filterKey) . ': ' . $name,
                    'is_query' => false,
                ];
            }
        }
    }
@endphp

<aside class="sp-filters" aria-label="فیلترها" @if($suggestUrl) data-suggest-url="{{ $suggestUrl }}" @endif>
    <div class="sp-filters__head">
        <h2 class="sp-filters__title">جستجو و فیلتر</h2>
        @if(count($active))
            <a class="sp-filters__clear" href="{{ $action }}">پاک کردن همه</a>
        @endif
    </div>

    @if(count($active))
        <div class="sp-filters__active chip-row" aria-label="فیلترهای فعال">
            @foreach($active as $chip)
                @php
                    if (!empty($chip['is_query'])) {
                        $href = request()->fullUrlWithQuery(['q' => null, 'page' => null]);
                    } else {
                        $href = request()->fullUrlWithQuery([
                            $chip['key'] => collect(request($chip['key'], []))->reject(fn ($v) => (string) $v === (string) $chip['value'])->values()->all(),
                            'page' => null,
                        ]);
                    }
                @endphp
                <a class="chip is-active" href="{{ $href }}">
                    {{ $chip['label'] }}
                    <span aria-hidden="true">×</span>
                </a>
            @endforeach
        </div>
    @endif

    <form id="filterForm" class="sp-filters__form" method="GET" action="{{ $action }}">
        <section class="sp-filters__group">
            <div class="sp-filters__group-head">
                <h3>جستجو</h3>
            </div>
            <label class="sp-search">
                <span class="sp-search__icon" aria-hidden="true">
                    <x-sp-icon :name="$searchIcon" />
                </span>
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    placeholder="{{ $searchPlaceholder }}"
                    autocomplete="off"
                    @if($suggestUrl) data-suggest-type="dog" @endif
                    class="sp-search__input"
                >
            </label>
            <div class="sp-suggest" data-suggest-panel hidden></div>
        </section>

        @foreach ($filters as $filterKey => $items)
            @php
                $label = $labelMap[$filterKey] ?? ucfirst($filterKey);
                $items = collect($items);
                $selected = collect(request($filterKey, []))->map(fn ($v) => (string) $v);
                $isSearchable = $searchable && in_array($filterKey, ['city', 'breed', 'specialty'], true);
            @endphp

            <section class="sp-filters__group" @if($isSearchable) data-filter-key="{{ $filterKey }}" @endif>
                <div class="sp-filters__group-head">
                    <h3>{{ $label }}</h3>
                </div>

                @if($isSearchable)
                    <script type="application/json" data-filter-options="{{ $filterKey }}">@json($items->map(function ($item) { return ['id' => is_object($item) ? $item->id : (is_array($item) ? ($item['id'] ?? $item) : $item), 'name' => is_object($item) ? $item->name : (is_array($item) ? ($item['name'] ?? $item) : $item)]; })->values())</script>
                    <div class="sp-typeahead" data-typeahead="{{ $filterKey }}">
                        <input
                            type="search"
                            class="sp-typeahead__input"
                            placeholder="جستجوی {{ $label }}…"
                            autocomplete="off"
                            data-suggest-type="{{ $filterKey }}"
                        >
                        <div class="sp-suggest" data-suggest-panel hidden></div>
                        <div class="sp-typeahead__selected chip-row">
                            @foreach($items as $item)
                                @php
                                    $value = is_object($item) ? (string) $item->id : (string) (is_array($item) ? $item['id'] : $item);
                                    $name = is_object($item) ? $item->name : (is_array($item) ? $item['name'] : $value);
                                @endphp
                                @if($selected->contains($value))
                                    <label class="chip is-active sp-filter-chip">
                                        <input type="checkbox" class="filter-checkbox" name="{{ $filterKey }}[]" value="{{ $value }}" checked>
                                        <span>{{ $name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="sp-filters__chips chip-row">
                        @foreach ($items as $item)
                            @php
                                $value = is_object($item) ? (string) ($item->id ?? '') : (string) (is_array($item) ? ($item['id'] ?? $item) : $item);
                                $name = is_object($item)
                                    ? ($item->name ?? $value)
                                    : (is_array($item) ? ($item['name'] ?? $value) : ($sizeLabels[$value] ?? ucfirst($value)));
                                $isChecked = $selected->contains($value);
                            @endphp
                            <label class="chip sp-filter-chip {{ $isChecked ? 'is-active' : '' }}" for="{{ $filterKey }}_{{ $value }}">
                                <input class="filter-checkbox"
                                       type="checkbox"
                                       id="{{ $filterKey }}_{{ $value }}"
                                       name="{{ $filterKey }}[]"
                                       value="{{ $value }}"
                                       {{ $isChecked ? 'checked' : '' }}>
                                <span>{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach

    </form>
</aside>