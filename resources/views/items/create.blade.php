<x-layouts.app>
    <section>
        <div class="text-2xl pb-4 mb-2 border-b border-line">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md px-4">
                <a class="flex size-9 justify-center items-center rounded-full hover:bg-highlight transition duration-300 ease-in-out justify-self-start"
                    href="{{ route('items.index') }}" aria-label="戻る"
                >
                    <x-icon.chevron-left />
                </a>
                <h2 class="text-center">登録</h2>
            </div>
        </div>
        <form class="mx-auto max-w-md p-4" method="POST" action="{{ route('items.store') }}">
            @csrf
            <div class="flex flex-col mb-4">
                <label class="mb-4" for="name">品名</label>
                <x-element.input
                    name="name"
                    autofocus
                    maxlength="255"
                    placeholder="例) キーボード"
                    required
                />
                @error('name')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-4" for="price">価格(¥)</label>
                <x-element.input
                    name="price"
                    type="number"
                    min="1"
                    max="10000000"
                    placeholder="例) 20000"
                    required
                />
                @error('price')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col mb-6">
                <label class="mb-4" for="memo">メモ</label>
                <x-element.textarea name="memo" />
                @error('memo')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-center">
                <button
                    class="w-full cursor-pointer rounded-3xl px-12 py-3 text-fg-on-emphasis bg-primary hover:bg-primary-hover transition duration-300"
                    type="submit"
                >登録</button>
            </div>
        </form>
    </section>
</x-layouts.app>
