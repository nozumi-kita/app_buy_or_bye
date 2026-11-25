@props(['href'])

<a href="{{ $href }}" aria-label="戻る"
    {{ $attributes->class('flex size-9 justify-center items-center hover:bg-highlight transition duration-300 ease-in-out rounded-full') }}
>
    <x-icon.chevron-left />
</a>
