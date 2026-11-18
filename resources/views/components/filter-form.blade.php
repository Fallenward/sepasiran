@props(['filters' => [], 'action' => request()->url()])

@php
    $labelMap = [
        'city' => 'شهر',
        'breed' => 'نژاد',
        'specialty' => 'تخصص',
        'size' => 'اندازه',
        'duration' => 'مدت زمان',
        'category' => 'دسته‌بندی',
        'brand' => 'برند',
        'gender' => 'جنسیت',
        'age' => 'سن',
        'price' => 'قیمت',
    ];

    $active = [];
    foreach ($filters as $filterKey => $items) {
        $items = is_array($items) ? collect($items) : collect($items);
        $selected = collect(request($filterKey, []));
        foreach ($items as $item) {
            $value = is_object($item) ? $item->id : $item;
            $name = is_object($item) ? $item->name : ucfirst((string) $item);
            if ($selected->map(fn ($v) => (string) $v)->contains((string) $value)) {
                $active[] = [
                    'key' => $filterKey,
                    'value' => $value,
                    'label' => ($labelMap[$filterKey] ?? $filterKey) . ': ' . $name,
                ];
            }
        }
    }
@endphp

<aside class="sp-filters" aria-label="فیلترها">
    <div class="sp-filters__head">
        <h2 class="sp-filters__title">فیلترها</h2>
        @if(count($active))
            <a class="sp-filters__clear" href="{{ $action }}">پاک کردن همه</a>
        @endif
    </div>

    @if(count($active))
        <div class="sp-filters__active chip-row" aria-label="فیلترهای فعال">
            @foreach($active as $chip)
                <a class="chip is-active"
                   href="{{ request()->fullUrlWithQuery([$chip['key'] => collect(request($chip['key'], []))->reject(fn ($v) => (string) $v === (string) $chip['value'])->values()->all()]) }}">
                    {{ $chip['label'] }}
                    <span aria-hidden="true">×</span>
                </a>
            @endforeach
        </div>
    @endif

    <form id="filterForm" class="sp-filters__form" method="GET" action="{{ $action }}">
        @foreach ($filters as $filterKey => $items)
            @php
                $label = $labelMap[$filterKey] ?? ucfirst($filterKey);
                $groupId = 'collapse_' . $filterKey;
                $items = is_array($items) ? collect($items) : collect($items);
                $selected = collect(request($filterKey, []))->map(fn ($v) => (string) $v);
            @endphp

            <section class="sp-filters__group">
                <div class="sp-filters__group-head">
                    <h3>{{ $label }}</h3>
                    @if($items->count() > 6)
                        <button class="sp-filters__more"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $groupId }}"
                                aria-expanded="false"
                                aria-controls="{{ $groupId }}">
                            بیشتر
                        </button>
                    @endif
                </div>

                <div class="sp-filters__chips chip-row">
                    @foreach ($items->take(6) as $item)
                        @php
                            $value = is_object($item) ? $item->id : $item;
                            $name = is_object($item) ? $item->name : ucfirst((string) $item);
                            $isChecked = $selected->contains((string) $value);
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

                @if($items->count() > 6)
                    <div class="collapse" id="{{ $groupId }}">
                        <div class="sp-filters__chips chip-row" style="margin-top:8px">
                            @foreach ($items->slice(6) as $item)
                                @php
                                    $value = is_object($item) ? $item->id : $item;
                                    $name = is_object($item) ? $item->name : ucfirst((string) $item);
                                    $isChecked = $selected->contains((string) $value);
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
                    </div>
                @endif
            </section>
        @endforeach
    </form>
</aside>