@props(['hasMore' => false, 'nextPage' => null])

@if($hasMore)
    <button id="load-more" class="btn btn-primary mt-4" data-next-page="{{ $nextPage }}">
        بارگذاری بیشتر
    </button>
@endif

