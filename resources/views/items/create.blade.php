<x-layouts.app>
    <section>
        <div class="text-2xl pb-4 mb-2 border-b border-line">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md px-4">
                <x-element.back-link :href="route('items.index')" />
                <h2>登録</h2>
            </div>
        </div>
        <form class="flex flex-col gap-4 mx-auto max-w-md p-4" method="POST" action="{{ route('items.store') }}">
            @csrf
            <div class="flex flex-col gap-2">
                <label for="name">品名</label>
                <x-element.input
                    name="name"
                    autofocus
                    maxlength="255"
                    placeholder="例) キーボード"
                    required
                />
                <x-element.input-error name="name" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="price">価格(¥)</label>
                <x-element.input
                    name="price"
                    type="number"
                    min="1"
                    max="10000000"
                    placeholder="例) 20000"
                    required
                />
                <x-element.input-error name="price" />
            </div>
            <div class="flex flex-col gap-2 mb-2">
                <label for="memo">メモ</label>
                <x-element.textarea name="memo" />
                <x-element.input-error name="memo" />
            </div>
            <div class="flex justify-center">
                <x-element.button type="submit">
                    登録
                </x-element.button>
            </div>
        </form>
    </section>
</x-layouts.app>
