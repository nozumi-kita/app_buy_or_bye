@props(['id' => null, 'name', 'type' => 'text', 'value' => ''])

<input
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ old($name, $value) }}"
    {{ $attributes->merge(['class' => 'w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line']) }}
>
