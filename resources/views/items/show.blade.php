<x-layouts.app>
    <section>
        <div class="text-center text-2xl border-b border-line pb-4 mb-4">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md">
                <a class="flex justify-center items-center size-9 hover:bg-highlight transition duration-300 ease-in-out rounded-full justify-self-start"
                    href="{{ route('items.index') }}" aria-label="戻る"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-7"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
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
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-7"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                        />
                    </svg>
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
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-7"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                            />
                        </svg>
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
