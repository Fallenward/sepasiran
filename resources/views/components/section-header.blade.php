<div class="header-section">
    <div>
        <h3 class="header-title">{{ $title }}</h3>

    </div>
    <x-line-divider></x-line-divider>
    <div>
        @if(!empty($buttonText))
        <a href="{{$link}}" class="btn btn-outline-dark border-0 button-max-content">{{ $buttonText }}</a>
        @endif
    </div>
</div>
