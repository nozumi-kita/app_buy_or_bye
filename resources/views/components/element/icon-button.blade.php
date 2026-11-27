@props(['href' => null, 'icon', 'label', 'iconSize' => 'size-7'])

@php
    $classes =
        'flex size-9 justify-center items-center hover:bg-highlight rounded-md transition duration-300 ease-in-out';
@endphp

<div
    x-data="{ tooltip: false }"
    @mouseenter="tooltip = true"
    @mouseleave="tooltip = false"
    {{ $attributes->class('relative shrink-0') }}
>
    @if ($href)
        <a href="{{ $href }}" aria-label="{{ $label }}" class="{{ $classes }}">
            <x-dynamic-component :component="'icon.' . $icon" :size="$iconSize" />
        </a>
    @else
        <button type="button" aria-label="{{ $label }}" class="{{ $classes }} cursor-pointer">
            <x-dynamic-component :component="'icon.' . $icon" :size="$iconSize" />
        </button>
    @endif
    <span
        x-show="tooltip"
        x-transition.duration.200ms
        x-cloak
        aria-hidden="true"
        class="pointer-events-none text-sm text-fg-on-emphasis top-full mt-1 bg-surface rounded px-2 py-1 absolute whitespace-nowrap left-1/2 -translate-x-1/2"
    >
        {{ $label }}
    </span>
</div>
