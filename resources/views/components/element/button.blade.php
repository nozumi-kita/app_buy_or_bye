@props(['theme' => 'primary', 'type' => 'button', 'fullWidth' => true, 'href' => null])

@php
    $themeClasses = [
        'primary' => 'bg-primary text-fg-on-emphasis duration-300 hover:bg-primary-hover',
        'secondary' => 'bg-secondary text-fg-on-emphasis duration-300 hover:bg-secondary-hover',
        'danger' =>
            'disabled:cursor-not-allowed text-danger border disabled:opacity-50 not-disabled:hover:text-fg-on-emphasis not-disabled:hover:bg-danger-solid-hover not-disabled:hover:border-danger-solid-hover duration-100',
    ];

    $baseClasses = 'py-3 cursor-pointer rounded-3xl transition';

    $attributes = $attributes->class([$baseClasses, 'w-full' => $fullWidth, $themeClasses[$theme]]);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes }}>{{ $slot }}</button>
@endif
