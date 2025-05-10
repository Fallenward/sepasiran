<div class="mb-5 p-5 {{ $extraClasses }}" style="background-color: {{ $bgColor }};">
    <h1 class="fw-bold mb-2">{{ $title }}</h1>

    @if(!empty($breadcrumbs))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['route'] }}" class="text-white text-decoration-none">
                            {{ $crumb['label'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</div>
