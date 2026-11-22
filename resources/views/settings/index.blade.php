<x-layouts.app>
    <section>
        <div class="text-center text-2xl border-b border-line pb-4 mb-4">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md">
                <a class="flex size-9 justify-center items-center hover:bg-highlight transition duration-300 ease-in-out rounded-full justify-self-start"
                    href="{{ route('items.index') }}" aria-label="戻る"
                >
                    <x-icon.chevron-left />
                </a>
                <h2>設定</h2>
            </div>
        </div>
        <div class="mx-auto max-w-md">
            @if (session('success'))
                <p class="flex justify-center gap-1 bg-flash-success p-3 rounded-3xl mb-4 text-fg-on-emphasis">
                    <x-icon.check-circle />
                    {{ session('success') }}
                </p>
            @endif
            <div class="mb-4 border border-line rounded-md">
                <div class="flex justify-between items-center px-4 py-1 mb-4">
                    <h3 class="text-xl">ユーザー情報</h3>
                    <a
                        class="flex justify-center items-center relative size-9 rounded-md hover:bg-highlight transition duration-300"
                        href="{{ route('account.edit') }}"
                        x-data="{ open: false }"
                        @mouseenter="open = true"
                        @mouseleave="open = false"
                        aria-label="編集"
                    >
                        <x-icon.pencil-square />
                        <span
                            class="pointer-events-none text-sm top-full mt-1 bg-surface rounded px-2 py-1 absolute whitespace-nowrap left-1/2 -translate-x-1/2"
                            x-show="open"
                            x-transition.duration.200ms
                            x-cloak
                        >編集
                        </span>
                    </a>
                </div>
                <dl class="px-4">
                    <div class="md:flex md:items-baseline mb-8 md:mb-6 md:gap-16">
                        <dt class="shrink-0 text-fg-muted mb-1 md:mb-0">ユーザー名</dt>
                        <dd class="break-all text-lg">{{ $user->name }}</dd>
                    </div>
                    <div class="md:flex md:items-baseline mb-6 md:gap-8">
                        <dt class="shrink-0 text-fg-muted mb-1 md:mb-0">メールアドレス</dt>
                        <dd class="break-all text-lg">{{ $user->email }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex flex-col rounded-md border border-line">
                <h3 class="text-xl px-4 pt-2 pb-4">アカウント削除</h3>
                <div class="flex flex-col" x-data="{ open: false }" @keydown.escape.window="open = false">
                    @error('password')
                        <p class="text-error mb-2 px-4 flex">
                            <x-icon.exclamation-circle />
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="px-4 pb-4">アカウントは一度削除すると戻せません。</p>
                    <button
                        class="self-end px-2 py-1 mb-4 mr-4 cursor-pointer text-danger border rounded-3xl hover:text-fg-on-emphasis hover:bg-danger-solid-hover hover:border-danger-solid-hover transition duration-100"
                        type="button" @click="open = !open"
                    >
                        アカウントを削除する
                    </button>
                    <div x-show="open" x-cloak
                        class="flex flex-col fixed inset-0 items-center justify-center text-fg bg-modal-bg z-10"
                    >
                        <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 border border-line">
                            <p class="text-danger">アカウントを一度削除すると二度と戻せません。本当によろしいですか？</p>
                            <div class="flex flex-col" x-data="{ password: '' }">
                                <form method="POST" action="{{ route('account.destroy') }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex flex-col">
                                        <label class="mb-4" for="password">パスワード</label>
                                        <input
                                            id="password"
                                            class="p-3 rounded-lg border border-line mb-4 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                                            name="password"
                                            type="password"
                                            placeholder="パスワードを入力"
                                            required
                                            x-model="password"
                                        />
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button"
                                            class="cursor-pointer py-3 rounded-3xl text-fg-on-emphasis bg-secondary hover:bg-secondary-hover transition duration-300"
                                            @click="open = false"
                                        >キャンセル</button>
                                        <button
                                            class="py-3 enabled:cursor-pointer text-danger border rounded-3xl enabled:hover:text-fg-on-emphasis enabled:hover:bg-danger-solid-hover enabled:hover:border-danger-solid-hover disabled:opacity-50 disabled:cursor-not-allowed transition duration-100"
                                            type="submit" :disabled="password === ''"
                                        >削除する</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
