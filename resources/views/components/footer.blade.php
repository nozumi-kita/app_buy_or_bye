<footer class="fixed border-t border-line bottom-0 bg-canvas md:hidden z-5 inset-x-0 pb-1">
    <ul class="grid auto-cols-fr grid-flow-col">
        <li>
            <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                href="{{ route('items.index') }}"
            >
                <x-icon.home />
                <span class="text-xs">ホーム</span>
            </a>
        </li>
        @auth
            <li>
                <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                    href="{{ route('medals') }}"
                >
                    <x-icon.trophy />
                    <span class="text-xs">実績</span>
                </a>
            </li>
            <li>
                <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                    href="{{ route('settings.index') }}"
                >
                    <x-icon.cog-8-tooth />
                    <span class="text-xs">設定</span>
                </a>
            </li>
            <li>
                <div x-data="{ open: false }" @keydown.escape.window="open = false">
                    <button
                        class="flex flex-col w-full cursor-pointer items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                        type="button" @click="open = !open"
                    >
                        <x-icon.arrow-right-start-on-rectangle />
                        <span class="text-xs">ログアウト</span>
                    </button>
                    <div x-show="open" x-cloak
                        class="flex flex-col fixed inset-0 items-center justify-center text-fg bg-modal-bg z-100"
                    >
                        <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                            @click.outside="open = false"
                        >
                            <p>ログアウトします。本当によろしいですか？</p>
                            <div class="grid grid-cols-2 gap-3">
                                <x-element.button theme="secondary" @click="open = false">
                                    キャンセル
                                </x-element.button>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-element.button type="submit">
                                        ログアウト
                                    </x-element.button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @else
            <li>
                <a class="flex flex-col items-center cursor-pointer gap-1 p-2 rounded-lg hover:bg-highlight text-fg transition duration-300"
                    href="{{ route('register') }}"
                >
                    <x-icon.user-plus />
                    <span class="text-xs text-center">アカウント作成</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div x-data="{ open: false }" @keydown.escape.window="open = false">
                        <button
                            class="w-full flex flex-col items-center cursor-pointer gap-1 p-2 rounded-lg hover:bg-highlight text-fg transition duration-300"
                            type="button" @click="open = !open"
                        >
                            <x-icon.arrow-right-start-on-rectangle />
                            <span class="text-xs">ゲスト終了</span>
                        </button>
                        <div x-show="open" x-cloak
                            class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg z-100"
                        >
                            <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                                @click.outside = "open = false"
                            >
                                <p class="mb-2">ゲストログインを終了すると、
                                    <span class="text-danger">登録したデータは二度と表示できません。</span>よろしいですか？
                                </p>
                                <div class="grid grid-cols-2 gap-3">
                                    <x-element.button theme="secondary" @click="open = false">
                                        キャンセル
                                    </x-element.button>
                                    <x-element.button type="submit">
                                        ゲスト終了
                                    </x-element.button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </li>
        @endauth
    </ul>
</footer>
