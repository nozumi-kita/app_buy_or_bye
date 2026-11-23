@props(['theme' => 'primary', 'type' => 'button', 'fullWidth' => true])

@php
    $themeClasses = [
        'primary' => 'bg-primary text-fg-on-emphasis duration-300 hover:bg-primary-hover',
        'secondary' => 'bg-secondary text-fg-on-emphasis duration-300 hover:bg-secondary-hover',
    ];

    $baseClasses = 'py-3 cursor-pointer rounded-3xl transition';
@endphp

<button type="{{ $type }}"
    {{ $attributes->class([$baseClasses, 'w-full' => $fullWidth, $themeClasses[$theme]]) }}
>{{ $slot }}</button>
