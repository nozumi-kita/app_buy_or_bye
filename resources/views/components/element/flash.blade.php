@props(['type' => 'success'])

@php
    $styles = [
        'success' => ['bg' => 'bg-flash-success', 'icon' => 'icon.check-circle'],
        'info' => ['bg' => 'bg-flash-info', 'icon' => 'icon.trophy'],
    ][$type];

    $baseClasses = 'flex justify-center gap-1 rounded-3xl p-3 text-fg-on-emphasis';

    $attributes = $attributes->class([$baseClasses, $styles['bg']]);
@endphp

<p {{ $attributes }}>
    <x-dynamic-component :component="$styles['icon']" />
    {{ $slot }}
</p>
