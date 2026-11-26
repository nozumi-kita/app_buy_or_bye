@props(['href' => null, 'icon'])

@php
    $classes = 'flex items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300';
@endphp

@if ($href)
    <a {{ $attributes->class($classes) }} href="{{ $href }}">
        <x-dynamic-component :component="'icon.' . $icon" />
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class([$classes, 'cursor-pointer']) }} type="button">
        <x-dynamic-component :component="'icon.' . $icon" />
        {{ $slot }}
    </button>
@endif
