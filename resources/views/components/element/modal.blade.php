{{--
    呼び出し側で、$triggerに「開くボタン」を入れ、$slotにモーダル内部を入れる。
    閉じるボタンを使用する場合、'<x-element.modal-close />'を呼び出すこと。
    モーダルの外側をクリックしても閉じないようにする場合、呼び出し側のタグに':close-click-outside="false"'を入れること
--}}
@props(['closeClickOutside' => true])

<div x-data="{ open: false }" @keydown.escape.window="open=false" {{ $attributes }}>
    <div class="contents" @click="open = true">{{ $trigger }}</div>
    <div x-show="open" x-cloak class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg z-100">
        <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 border border-line"
            @if ($closeClickOutside) @click.outside="open = false" @endif
        >
            {{ $slot }}
        </div>
    </div>
</div>
