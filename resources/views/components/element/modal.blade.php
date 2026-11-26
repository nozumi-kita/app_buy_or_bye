{{-- 呼び出し側で、$triggerに「開くボタン」を入れ、$slotにモーダル内部を入れる。閉じるボタンは<x-element.modal-close />を呼び出すこと。 --}}

<div x-data="{ open: false }" @keydown.escape.window="open=false">
    <div @click="open = true">{{ $trigger }}</div>
    <div x-show="open" x-cloak class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg z-100">
        <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 border border-line"
            @click.outside="open = false"
        >
            {{ $slot }}
        </div>
    </div>
</div>
