@props(['items' => []])
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0 small p-0" style="background:transparent;">
        @foreach($items as $label => $url)
            @if(is_null($url) || $loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url }}" class="text-decoration-none text-brand">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
