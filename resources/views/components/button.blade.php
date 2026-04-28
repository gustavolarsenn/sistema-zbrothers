@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])

@if ($href)
    <a {{ $attributes->merge(['class' => 'btn '.$variant, 'href' => $href]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => 'btn '.$variant, 'type' => $type]) }}>{{ $slot }}</button>
@endif
