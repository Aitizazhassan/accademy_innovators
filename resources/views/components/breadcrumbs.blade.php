<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$loop->last)
                    @if (isset($breadcrumb['url']))
                        @if (\Illuminate\Support\Str::contains(request()->url(), '/estimates'))
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a>
                        </li>
                        @else
                        {{-- <li class="breadcrumb-item"><a href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['name'] }}</a>
                        </li> --}}
                        <li class="breadcrumb-item">{{ $breadcrumb['name'] }}
                        </li>
                        @endif
                    @else
                        @if (\Illuminate\Support\Str::contains(request()->url(), '/estimates'))
                        <li class="breadcrumb-item">Estimates / {{ $breadcrumb['name'] }}</li>
                        @else
                        <li class="breadcrumb-item">{{ $breadcrumb['name'] }}</li>
                        @endif
                    @endif
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
</div>
