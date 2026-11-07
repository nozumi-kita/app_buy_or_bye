<x-layouts.guest>
    <section class="w-full md:grid md:grid-cols-2">
        <section class="hidden border-line border-r-2 md:block">
            <div class="flex flex-col items-center min-h-dvh justify-center">
                <img class="block size-25 mb-2" src="{{ asset('images/app-logo.svg') }}" alt="Buy or Bye">
                <h2 class="text-3xl">Look before you leap.</h2>
            </div>
        </section>
        <section class="mx-auto w-full max-w-md px-2 flex flex-col min-h-dvh justify-center">
            @if (session('success'))
                <p
                    class="flex items-center justify-center rounded-3xl bg-flash-success text-fg-on-emphasis p-3 mb-4 mx-4 md:mx-6">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                    {{ session('success') }}
                </p>
            @endif
            <img class="block mx-auto size-15 mb-4 md:hidden" src="{{ asset('images/app-logo.svg') }}" alt="Buy or Bye">
            <h1 class="mb-4 text-2xl text-center md:mb-8">ログイン</h1>
            <div>
                <div class="mx-4 md:mx-6 md:mb-2">
                    <form class="gap-6 flex flex-col md:gap-8" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div>
                            <label class="block mb-2" for="email">メールアドレス</label>
                            <input
                                id="email"
                                class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                placeholder="user@example.com"
                                autofocus
                                required
                            >
                            @error('email')
                                <p class="text-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block mb-2" for="password">パスワード</label>
                            <input
                                id="password"
                                class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="パスワードを入力"
                                required
                            >
                            @error('password')
                                <p class="text-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <button
                            class="w-full cursor-pointer rounded-3xl bg-primary px-8 py-3 text-center text-fg-on-emphasis transition duration-300 hover:bg-primary-hover"
                            type="submit"
                        >ログイン</button>
                    </form>
                    <div class="relative flex items-center justify-center my-2 md:my-6">
                        <span class="absolute inset-x-0 h-px bg-line"></span>
                        @if (!$guestLoggedIn)
                            <span class="relative px-4 bg-canvas text-fg">or</span>
                        @endif
                    </div>
                    @if ($guestLoggedIn)
                        <div class="flex flex-col border border-line rounded-xl px-2 py-2">
                            <a class="flex self-center mb-2 text-link hover:underline hover:underline-offset-2 hover:text-link-hover hover:decoration-2"
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
                                ホームに戻る
                            </a>
                            <p class="mx-1">
                                ゲストログイン中です。データを引き継いで利用を開始する場合、下記リンクよりアカウントを作成してください。
                            </p>
                        </div>
                    @else
                        <div>
                            <div x-data="{ open: false }" @keydown.escape.window="open=false">
                                <button
                                    class="w-full cursor-pointer rounded-3xl bg-secondary px-8 py-3 text-fg-on-emphasis transition duration-300 hover:bg-secondary-hover"
                                    type="button" @click="open = !open"
                                >ゲストログイン</button>
                                <div x-show="open" x-cloak
                                    class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg"
                                >
                                    <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                                        @click.outside="open = false"
                                    >
                                        <p class="mb-2">ゲストログインを開始します。よろしいですか？</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <button
                                                class="cursor-pointer bg-secondary hover:bg-secondary-hover text-fg-on-emphasis p-2 rounded-3xl transition duration-300"
                                                type="button" @click="open = false"
                                            >キャンセル</button>
                                            <form method="POST" action="{{ route('guest-login') }}">
                                                @csrf
                                                <button
                                                    class="w-full cursor-pointer text-fg-on-emphasis gap-1 py-3 rounded-3xl bg-primary hover:bg-primary-hover transition duration-300"
                                                    type="submit"
                                                >
                                                    ゲストログイン開始
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-center p-4">
                    <p>アカウントをお持ちでない方：
                        <a href="{{ route('register') }}"
                            class="text-link hover:underline hover:underline-offset-4 hover:text-link-hover"
                        >ユーザー登録
                        </a>
                    </p>
                </div>
            </div>
        </section>
    </section>
</x-layouts.guest>
