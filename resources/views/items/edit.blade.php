@use('\App\Enums\ItemStatus')

<x-layouts.app>
    <section>
        <div class="text-2xl pb-4 mb-2 border-b border-line">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md px-4">
                <a class="flex size-9 justify-center items-center rounded-full hover:bg-highlight transition duration-300 ease-in-out justify-self-start"
                    href="{{ route('items.index') }}" aria-label="戻る"
                >
                    <x-icon.chevron-left />
                </a>
                <h2>編集</h2>
            </div>
        </div>
        <form class="mx-auto max-w-md p-4" method="POST" action="{{ route('items.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="flex flex-col mb-4">
                <label class="mb-4" for="name">品名</label>
                <x-element.input
                    name="name"
                    :value="$item->name"
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
                    :value="$item->price"
                    placeholder="例) 20000"
                    required
                />
                @error('price')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-4" for="memo">メモ</label>
                <x-element.textarea name="memo" :value="$item->memo" />
                @error('memo')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-4" for="status">ステータス</label>
                <select
                    class="w-30 border border-line rounded-lg p-3 focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                    name="status" id="status"
                >
                    @foreach (ItemStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $item->status->value) === $status->value)>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button
                    class="w-full cursor-pointer rounded-3xl px-12 py-3 text-fg-on-emphasis bg-primary hover:bg-primary-hover transition duration-300"
                    type="submit"
                >更新</button>
            </div>
        </form>
    </section>
</x-layouts.app>
