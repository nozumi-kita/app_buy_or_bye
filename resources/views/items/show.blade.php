<x-layouts.app>
    <section>
        <div class="text-center text-2xl border-b border-line pb-4 mb-4">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md">
                <a class="flex justify-center items-center size-9 hover:bg-highlight transition duration-300 ease-in-out rounded-full justify-self-start"
                    href="{{ route('items.index') }}" aria-label="戻る"
                >
                    <x-icon.chevron-left />
                </a>
                <h2>詳細</h2>
            </div>
        </div>
        <div class="mx-auto max-w-md border border-line rounded-md p-4">
            <dl class="flex flex-col md:gap-10 gap-7 mb-6">
                <div class="md:flex md:items-baseline gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">品名</dt>
                    <dd class="break-all text-xl">{{ $item->name }}</dd>
                </div>
                <div class="md:flex md:items-baseline md:gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">価格</dt>
                    <dd class="break-all text-xl">{{ format_jpy($item->price) }}</dd>
                </div>
                <div class="md:flex md:items-baseline md:gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">メモ</dt>
                    <dd class="break-all">{{ $item->memo }}</dd>
                </div>
                <div class="md:flex md:gap-8">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">更新日時</dt>
                    <dd class="break-all">{{ format_jst($item->updated_at) }}</dd>
                </div>
                <div class="md:flex gap-4">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">ステータス</dt>
                    <dd class="break-all">{{ $item->status->label() }}</dd>
                </div>
            </dl>
            <div class="flex justify-between items-center" x-data="{ open: false, deleteOpen: false }">
                <button
                    class="flex size-9 justify-center items-center relative cursor-pointer hover:bg-highlight rounded-md transition duration-300 ease-in-out"
                    type="button"
                    aria-label="削除"
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                    @click="deleteOpen = !deleteOpen"
                    @keydown.escape.window="deleteOpen = false"
                >
                    <x-icon.trash />
                    <span
                        class="pointer-events-none text-sm text-fg-on-emphasis top-full mt-1 bg-surface rounded px-2 py-1 absolute whitespace-nowrap left-1/2 -translate-x-1/2"
                        x-show="open"
                        x-transition.duration.200ms
                        x-cloak
                    >削除
                    </span>
                </button>
                <div x-show="deleteOpen" x-cloak
                    class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg z-10"
                >
                    <div class="flex flex-col gap-3 bg-canvas rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                        @click.outside="deleteOpen = false"
                    >
                        <p>削除します。よろしいですか？</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                class="cursor-pointer bg-secondary hover:bg-secondary-hover py-3 rounded-3xl text-fg-on-emphasis transition duration-300"
                                type="button" @click="deleteOpen = false"
                            >キャンセル</button>
                            <form method="POST" action="{{ route('items.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="w-full py-3 cursor-pointer text-danger border rounded-3xl hover:text-fg-on-emphasis hover:bg-danger-solid-hover hover:border-danger-solid-hover transition duration-100"
                                    type="submit"
                                >
                                    削除する
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <a
                        class="flex justify-center items-center size-9 relative hover:bg-highlight transition duration-300 ease-in-out rounded-md"
                        aria-label="編集"
                        href="{{ route('items.edit', $item) }}"
                        x-data="{ open: false }"
                        @mouseenter="open = true"
                        @mouseleave="open = false"
                    >
                        <x-icon.pencil-square />
                        <span
                            class="pointer-events-none text-sm top-full mt-1 text-fg-on-emphasis bg-surface rounded px-2 py-1 absolute whitespace-nowrap left-1/2 -translate-x-1/2"
                            x-show="open"
                            x-transition.duration.200ms
                            x-cloak
                        >編集
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
