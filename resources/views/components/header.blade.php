<header class="border-b border-line">
    <div class="flex justify-between mx-auto max-w-240 px-4">
        <h1 class="mr-2 my-2">
            <a href="{{ route('items.index') }}">
                <img class="h-15 w-15 hover:brightness-120 transition duration-300"
                    src="{{ asset('images/app-logo.svg') }}" alt="Buy or Bye"
                >
            </a>
        </h1>
        <nav class="hidden md:flex items-center">
            <ul class="flex items-center gap-4">
                <li>
                    <a class="flex items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                        href="{{ route('items.index') }}"
                    >
                        <x-icon.home />
                        <span>ホーム</span>
                    </a>
                </li>
                @auth
                    <li>
                        <a class="flex items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                            href="{{ route('medals') }}"
                        >
                            <x-icon.trophy />
                            <span>実績</span>
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                            href="{{ route('settings.index') }}"
                        >
                            <x-icon.cog-8-tooth />
                            <span>設定</span>
                        </a>
                    </li>
                    <li>
                        <div x-data="{ open: false }" @keydown.escape.window="open = false">
                            <button
                                class="cursor-pointer flex items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                                type="button" @click="open = !open"
                            >
                                <x-icon.arrow-right-start-on-rectangle />
                                ログアウト
                            </button>
                            <div x-show="open" x-cloak
                                class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg z-100"
                            >
                                <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                                    @click.outside="open = false"
                                >
                                    <p class="mb-2">ログアウトします。本当によろしいですか？</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button
                                            class="cursor-pointer bg-secondary hover:bg-secondary-hover py-3 rounded-3xl text-fg-on-emphasis transition duration-300"
                                            type="button" @click="open = false"
                                        >キャンセル</button>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button
                                                class="w-full cursor-pointer py-3 rounded-3xl text-fg-on-emphasis bg-primary hover:bg-primary-hover transition duration-300"
                                                type="submit"
                                            >
                                                ログアウト
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @else
                    <li>
                        <a class="flex items-center gap-1 hover:bg-highlight p-2 rounded-lg transition duration-300"
                            href="{{ route('register') }}"
                        >
                            <x-icon.user-plus />
                            <span>アカウント作成</span>
                        </a>
                    </li>
                    <li>
                        <div x-data="{ open: false }" @keydown.escape.window="open = false">
                            <button
                                class="flex items-center cursor-pointer gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                                type="button" @click="open = !open"
                            >
                                <x-icon.arrow-right-start-on-rectangle />
                                ゲストログイン終了
                            </button>
                            <div x-show="open" x-cloak
                                class="flex flex-col fixed inset-0 items-center justify-center text-fg bg-modal-bg z-100"
                            >
                                <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-4 max-w-sm mx-4 mt-2 border border-line"
                                    @click.outside = "open = false"
                                >
                                    <p class="mb-2">ゲストログインを終了すると、<span
                                            class="text-danger">登録したデータは二度と表示できません。</span>よろしいですか？</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button
                                            class="cursor-pointer bg-secondary hover:bg-secondary-hover py-3 rounded-3xl text-fg-on-emphasis transition duration-300"
                                            type="button" @click="open = false"
                                        >キャンセル</button>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button
                                                class="cursor-pointer w-full py-3 text-fg-on-emphasis rounded-3xl bg-primary hover:bg-primary-hover transition duration-300"
                                                type="submit"
                                            >
                                                ゲストログイン終了
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>
