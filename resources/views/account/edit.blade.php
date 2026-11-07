<x-layouts.app>
    <section>
        <div class="text-2xl pb-4 mb-2 border-b border-line px-4">
            <div class="mx-auto max-w-md grid grid-cols-[1fr_auto_1fr] md:px-4">
                <a class="size-9 flex justify-center items-center rounded-full hover:bg-highlight transition duration-300 ease-in-out justify-self-start"
                    href="{{ route('settings.index') }}" aria-label="戻る"
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
                <h2 class="text-center">ユーザー情報編集</h2>
            </div>
        </div>
        <form class="flex flex-col p-4 gap-4 mx-auto max-w-md" method="POST" action="{{ route('account.update') }}">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-2">
                <label for="name">ユーザー名</label>
                <input
                    class="border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    placeholder="例) 山田太郎"
                    required
                >
                @error('name')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="email">メールアドレス</label>
                <input
                    class="border border-line rounded-lg p-3 placeholder:text-muted focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    placeholder="例) user@example.com"
                    required
                >
                @error('email')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2 mb-2">
                <label for="password">パスワード</label>
                <input
                    class="border border-line rounded-lg placeholder:text-muted p-3 focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                    name="password"
                    id="password"
                    type="password"
                    placeholder="パスワードを入力してください"
                    required
                >
                @error('password')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex">
                <button
                    class="w-full cursor-pointer px-10 py-3 text-fg-on-emphasis rounded-3xl bg-primary hover:bg-primary-hover transition duration-300"
                    type="submit"
                >更新</button>
            </div>
        </form>
    </section>
</x-layouts.app>
