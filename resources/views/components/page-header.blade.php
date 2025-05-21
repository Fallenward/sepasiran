<div class="mb-5 p-5 {{ $extraClasses }}" style="background-image: {{ 'linear-gradient(90deg, #0D47A1 0%, #051A3B 100%)' }};">
    <h1 class="fw-bold mb-2 text-center text-white">{{ $title }}</h1>

    @if(!empty($breadcrumbs))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item {{ empty($crumb['route']) ? 'active text-white' : '' }}"
                        @if(empty($crumb['route'])) aria-current="page" @endif>
                        @if(!empty($crumb['route']))
                            <a href="{{ $crumb['route'] }}" class="text-white text-decoration-none">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            {{ $crumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</div>
