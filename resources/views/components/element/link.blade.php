@props(['href'])

<a {{ $attributes->class(['hover:underline hover:underline-offset-4 text-link hover:text-link-hover']) }}
    href="{{ $href }}"
>
    {{ $slot }}
</a>
