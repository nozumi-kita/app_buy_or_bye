@props(['label' => 'キャンセル'])

<x-element.button theme="secondary" @click="modal = false">
    {{ $label }}
</x-element.button>
