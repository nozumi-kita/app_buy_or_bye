@props(['label' => 'キャンセル'])

<x-element.button theme="secondary" @click="open = false">
    {{ $label }}
</x-element.button>
