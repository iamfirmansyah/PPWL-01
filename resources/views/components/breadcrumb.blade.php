<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        @foreach($breadcrumbs as $breadcrumb)
            @if($loop->last)
                <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
