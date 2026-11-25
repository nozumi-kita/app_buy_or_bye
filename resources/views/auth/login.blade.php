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
                <x-element.flash class="mb-4 mx-4 md:mx-6">
                    {{ session('success') }}
                </x-element.flash>
            @endif
            <img class="block mx-auto size-15 mb-4 md:hidden" src="{{ asset('images/app-logo.svg') }}" alt="Buy or Bye">
            <h1 class="mb-4 text-2xl text-center md:mb-8">ログイン</h1>
            <div>
                <div class="mx-4 md:mx-6 md:mb-2">
                    <form class="gap-6 flex flex-col md:gap-8" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="flex flex-col gap-2">
                            <label for="email">メールアドレス</label>
                            <x-element.input
                                name="email"
                                type="email"
                                autocomplete="username"
                                placeholder="user@example.com"
                                autofocus
                                required
                            />
                            <x-element.input-error name="email" class="mt-2" />
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="password">パスワード</label>
                            <x-element.input
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="パスワードを入力"
                                required
                            />
                            <x-element.input-error name="password" class="mt-2" />
                        </div>
                        <x-element.button type="submit">
                            ログイン
                        </x-element.button>
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
                                <x-icon.home />
                                ホームに戻る
                            </a>
                            <p class="mx-1">
                                ゲストログイン中です。データを引き継いで利用を開始する場合、下記リンクよりアカウントを作成してください。
                            </p>
                        </div>
                    @else
                        <div>
                            <div x-data="{ open: false }" @keydown.escape.window="open=false">
                                <x-element.button theme="secondary" @click="open = !open">
                                    ゲストログイン
                                </x-element.button>
                                <div x-show="open" x-cloak
                                    class="flex flex-col fixed inset-0 items-center justify-center bg-modal-bg"
                                >
                                    <div class="flex flex-col gap-3 bg-modal rounded-xl px-4 py-3 max-w-sm mx-4 mt-2 border border-line"
                                        @click.outside="open = false"
                                    >
                                        <p class="mb-2">ゲストログインを開始します。よろしいですか？</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <x-element.button theme="secondary" @click="open = false">
                                                キャンセル
                                            </x-element.button>
                                            <form method="POST" action="{{ route('guest-login') }}">
                                                @csrf
                                                <x-element.button type="submit">
                                                    ゲストログイン
                                                </x-element.button>
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
