@props(['name'])

@error($name)
    <p {{ $attributes->class(['text-error']) }}>
        {{ $message }}
    </p>
@enderror
