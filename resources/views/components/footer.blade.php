<footer class="fixed border-t border-line bottom-0 bg-canvas md:hidden z-5 inset-x-0 pb-1">
    <ul class="grid auto-cols-fr grid-flow-col">
        <li>
            <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                href="{{ route('items.index') }}"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                    />
                </svg>
                <span class="text-xs">ホーム</span>
            </a>
        </li>
        @auth
            <li>
                <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                    href="{{ route('medals') }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                        />
                    </svg>
                    <span class="text-xs">実績</span>
                </a>
            </li>
            <li>
                <a class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                    href="{{ route('settings.index') }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"
                        />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span class="text-xs">設定</span>
                </a>
            </li>
            <li>
                <div x-data="{ open: false }" @keydown.escape.window="open = false">
                    <button
                        class="flex flex-col w-full cursor-pointer items-center gap-1 p-2 rounded-lg hover:bg-highlight transition duration-300"
                        type="button" @click="open = !open"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-6"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                            />
                        </svg>
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
                                <button
                                    class="cursor-pointer bg-secondary hover:bg-secondary-hover py-3 text-fg-on-emphasis rounded-3xl transition duration-300"
                                    type="button" @click="open = false"
                                >キャンセル</button>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button
                                        class="w-full cursor-pointer py-3 text-fg-on-emphasis rounded-3xl bg-primary hover:bg-primary-hover transition duration-300"
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
                <a class="flex flex-col items-center cursor-pointer gap-1 p-2 rounded-lg hover:bg-highlight text-fg transition duration-300"
                    href="{{ route('register') }}"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"
                        />
                    </svg>
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
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                                />
                            </svg>
                            <span class="text-xs">ゲストログイン終了</span>
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
                                    <button
                                        class="cursor-pointer bg-secondary hover:bg-secondary-hover py-3 rounded-3xl text-fg-on-emphasis transition duration-300"
                                        type="button" @click="open = false"
                                    >キャンセル</button>
                                    <button
                                        class="cursor-pointer py-3 rounded-3xl text-fg-on-emphasis bg-primary hover:bg-primary-hover transition duration-300"
                                        type="submit"
                                    >
                                        <span>ゲストログイン終了</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </li>
        @endauth
    </ul>
</footer>
