<div class="col-12 d-flex align-items-center justify-content-between">
    <div>
        <h3>{{ $title }}</h3>
      <x-line-divider></x-line-divider>
    </div>
    <div>
        @if(!empty($buttonText))
        <a href="{{$link}}" class="btn btn-light">{{ $buttonText }}</a>
        @endif
    </div>
</div>
