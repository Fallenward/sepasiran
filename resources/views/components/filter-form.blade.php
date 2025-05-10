@props(['filters' => [], 'action' => request()->url()])

<div class="p-3 bg-light rounded w-100" style="direction: rtl; max-width: 350px;">
    <h5 class="fw-bold">فیلترها</h5>

    <form id="filterForm" method="GET" action="{{ $action }}">
        @foreach ($filters as $filterKey => $items)
            @php
                $label = [
                    'city' => 'شهر',
                    'breed' => 'نژاد',
                    'specialty' => 'تخصص',
                    'size' => 'اندازه',
                    'duration' => 'مدت زمان'
                ][$filterKey] ?? ucfirst($filterKey);


                $groupId = 'collapse_' . $filterKey;
                $items = is_array($items) ? collect($items) : $items;
            @endphp

            <div class="mb-4 border rounded p-2">
                <div class="d-flex justify-content-between align-items-center mb-2 px-2 py-1 rounded" style="background-color: #0d47a1;">
                    <h6 class="text-white mb-0">{{ $label }}</h6>

                    @if($items->count() > 4)
                        <button class="btn btn-sm text-white border-0"
                                style="background: transparent; font-size: 1.2rem;"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $groupId }}"
                                aria-expanded="false"
                                aria-controls="{{ $groupId }}">
                            <i class="fa fa-arrow-left"></i>
                            
                        </button>
                    @endif
                </div>

                {{-- First 4 items --}}
                @foreach ($items->take(4) as $item)
                    @php
                        $value = is_object($item) ? $item->id : $item;
                        $name = is_object($item) ? $item->name : ucfirst($item);
                        $isChecked = in_array($value, request($filterKey, []));
                    @endphp

                    <div class="form-check d-flex justify-content-between align-items-center mb-2 text-dark">
                        <label class="form-check-label me-2" for="{{ $filterKey }}_{{ $value }}">
                            {{ $name }}
                        </label>
                        <input class="form-check-input filter-checkbox" type="checkbox"
                               id="{{ $filterKey }}_{{ $value }}"
                               name="{{ $filterKey }}[]"
                               value="{{ $value }}"
                               {{ $isChecked ? 'checked' : '' }}>
                    </div>
                @endforeach

                {{-- Collapsible extra items --}}
                @if($items->count() > 4)
                    <div class="collapse mt-2" id="{{ $groupId }}">
                        @foreach ($items->slice(4) as $item)
                            @php
                                $value = is_object($item) ? $item->id : $item;
                                $name = is_object($item) ? $item->name : ucfirst($item);
                                $isChecked = in_array($value, request($filterKey, []));
                            @endphp

                            <div class="form-check d-flex justify-content-between align-items-center mb-2 text-dark">
                                <label class="form-check-label me-2" for="{{ $filterKey }}_{{ $value }}">{{ $name }}</label>
                                <input class="form-check-input filter-checkbox" type="checkbox"
                                       id="{{ $filterKey }}_{{ $value }}"
                                       name="{{ $filterKey }}[]"
                                       value="{{ $value }}"
                                       {{ $isChecked ? 'checked' : '' }}>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.filter-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });
});
</script>
@endpush
