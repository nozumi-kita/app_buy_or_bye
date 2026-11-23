<x-layouts.app>
    <section>
        <div class="text-2xl pb-4 mb-2 border-b border-line px-4">
            <div class="mx-auto max-w-md grid grid-cols-[1fr_auto_1fr] md:px-4">
                <a class="size-9 flex justify-center items-center rounded-full hover:bg-highlight transition duration-300 ease-in-out justify-self-start"
                    href="{{ route('settings.index') }}" aria-label="戻る"
                >
                    <x-icon.chevron-left />
                </a>
                <h2 class="text-center">ユーザー情報編集</h2>
            </div>
        </div>
        <form class="flex flex-col p-4 gap-4 mx-auto max-w-md" method="POST" action="{{ route('account.update') }}">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-2">
                <label for="name">ユーザー名</label>
                <x-element.input
                    name="name"
                    :value="$user->name"
                    placeholder="例) 山田太郎"
                    required
                />
                @error('name')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label for="email">メールアドレス</label>
                <x-element.input
                    name="email"
                    type="email"
                    :value="$user->email"
                    placeholder="例) user@example.com"
                    required
                />
                @error('email')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col gap-2 mb-2">
                <label for="password">パスワード</label>
                <x-element.input
                    name="password"
                    type="password"
                    placeholder="パスワードを入力してください"
                    required
                />
                @error('password')
                    <p class="text-error mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex">
                <x-element.button type="submit">
                    更新
                </x-element.button>
            </div>
        </form>
    </section>
</x-layouts.app>
