@props(['href' => null, 'icon'])

@php
    $classes = 'flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300 md:flex-row';
@endphp

@if ($href)
    <a {{ $attributes->class($classes) }} href="{{ $href }}">
        <x-dynamic-component :component="'icon.' . $icon" />
        <span class="text-center text-xs md:text-base">{{ $slot }}</span>
    </a>
@else
    <button {{ $attributes->class([$classes, 'w-full cursor-pointer']) }} type="button">
        <x-dynamic-component :component="'icon.' . $icon" />
        <span class="text-center text-xs md:text-base">{{ $slot }}</span>
    </button>
@endif
