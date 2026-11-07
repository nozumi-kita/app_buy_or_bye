<x-layouts.guest>
    <section class="mx-auto w-full max-w-md px-2 flex flex-col min-h-dvh justify-center">
        @if ($guestLoggedIn)
            <a class="inline-flex self-start mx-4 mt-4 mb-4 hover:underline hover:underline-offset-2 text-link hover:text-link-hover md:mx-6"
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
        @else
            <a class="size-9 flex justify-center items-center rounded-full mx-2 hover:bg-highlight transition duration-300 ease-in-out md:mx-6 self-start"
                href="{{ route('login') }}" aria-label="戻る"
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
        @endif

        <h1 class="mb-4 text-2xl text-center">ユーザー登録</h1>
        @if ($guestLoggedIn)
            <p class="mx-4 mb-3 md:mx-6">
                ゲストログイン中に登録したデータは、新規のアカウントに引き継がれます。
            </p>
        @endif
        <div>
            <div class="mx-4 mb-4 md:mx-6">
                <form class="flex flex-col gap-4" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div>
                        <label class="block mb-2" for="name">ユーザー名</label>
                        <input
                            id="name"
                            class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            placeholder="例) 山田太郎"
                            autofocus
                            required
                        >
                        @error('name')
                            <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2" for="email">メールアドレス</label>
                        <input
                            id="email"
                            class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            placeholder="例) user@example.com"
                            required
                        >
                        @error('email')
                            <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label class="block mb-2" for="password">パスワード</label>
                        <input
                            id="password"
                            class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            placeholder="例) TafG9@3b"
                            required
                        >
                        <p class="text-sm mt-2">8文字以上で、大文字・小文字・数字・記号を全て含むこと</p>
                        @error('password')
                            <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block mb-2" for="password_confirmation">パスワード確認</label>
                        <input
                            id="password_confirmation"
                            class="w-full border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="もう一度同じパスワードを入力"
                            required
                        >
                    </div>
                    <button
                        class="w-full cursor-pointer rounded-3xl bg-primary text-fg-on-emphasis px-8 py-3 transition duration-300 hover:bg-primary-hover"
                        type="submit"
                    >ユーザー登録</button>
                </form>
            </div>
            @if (!$guestLoggedIn)
                <div class="flex items-center justify-center p-4">
                    <p>アカウントをお持ちの方：<a class="hover:underline hover:underline-offset-4 text-link hover:text-link-hover"
                            href="{{ route('login') }}"
                        >ログイン</a>
                    </p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.guest>
