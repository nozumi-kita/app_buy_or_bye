@props(['id' => null, 'name', 'value' => '', 'rows' => '3', 'maxlength' => '500'])

<textarea
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    maxlength="{{ $maxlength }}"
    {{ $attributes->merge(['class' => 'border border-line rounded-lg p-3 focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line placeholder:text-muted']) }}
>{{ old($name, $value) }}</textarea>
