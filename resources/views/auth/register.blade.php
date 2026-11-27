<x-layouts.guest>
    <section class="mx-auto w-full max-w-md px-2 flex flex-col min-h-dvh justify-center py-4">
        @if ($guestLoggedIn)
            <x-element.link :href="route('items.index')" class="mx-4 mb-4 md:mx-6 inline-flex self-start">
                <x-icon.home />
                ホームに戻る
            </x-element.link>
        @else
            <x-element.back-link :href="route('login')" class="mx-2 md:mx-6" />
        @endif
        <h1 class="mb-4 text-2xl text-center">ユーザー登録</h1>
        @if ($guestLoggedIn)
            <p class="mx-4 mb-3 md:mx-6">
                ゲストログイン中に登録したデータは、新規のアカウントに引き継がれます。
            </p>
        @endif
        <div>
            <div class="mx-4 md:mx-6">
                <form class="flex flex-col gap-4" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="name">ユーザー名</label>
                        <x-element.input
                            name="name"
                            autocomplete="name"
                            placeholder="例) 山田太郎"
                            autofocus
                            required
                        />
                        <x-element.input-error name="name" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="email">メールアドレス</label>
                        <x-element.input
                            name="email"
                            type="email"
                            autocomplete="username"
                            placeholder="例) user@example.com"
                            required
                        />
                        <x-element.input-error name="email" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password">パスワード</label>
                        <x-element.input
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            placeholder="パスワードを入力"
                            required
                        />
                        <p class="text-sm">8文字以上で、大文字・小文字・数字・記号を全て含むこと</p>
                        <x-element.input-error name="password" />
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password_confirmation">パスワード確認</label>
                        <x-element.input
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="もう一度同じパスワードを入力"
                            required
                        />
                    </div>
                    <x-element.button type="submit">
                        ユーザー登録
                    </x-element.button>
                </form>
            </div>
            @if (!$guestLoggedIn)
                <div class="flex items-center justify-center p-4">
                    <p>アカウントをお持ちの方：
                        <x-element.link :href="route('login')">
                            ログイン
                        </x-element.link>
                    </p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.guest>
