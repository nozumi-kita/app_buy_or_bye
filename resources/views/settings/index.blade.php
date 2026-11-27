<x-layouts.app>
    <section>
        <div class="text-center text-2xl border-b border-line pb-4 mb-4">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md">
                <x-element.back-link :href="route('items.index')" />
                <h2>設定</h2>
            </div>
        </div>
        <div class="mx-auto max-w-md">
            @if (session('success'))
                <x-element.flash class="mb-4">
                    {{ session('success') }}
                </x-element.flash>
            @endif
            <div class="mb-4 border border-line rounded-md">
                <div class="flex justify-between items-center px-4 py-1 mb-4">
                    <h3 class="text-xl">ユーザー情報</h3>
                    <x-element.icon-button :href="route('account.edit')" icon="pencil-square" label="編集" />
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
                <div class="flex flex-col">
                    <x-element.input-error name="password" class="mb-2 px-4" />
                    <p class="px-4 pb-4">アカウントは一度削除すると戻せません。</p>
                    <x-element.modal :close-click-outside="false" class="self-end mb-4 mr-4">
                        <x-slot:trigger>
                            <x-element.button theme="danger" :full-width="false" class="px-3">
                                アカウント削除
                            </x-element.button>
                        </x-slot:trigger>
                        <p class="text-danger">アカウントを一度削除すると二度と戻せません。本当によろしいですか？</p>
                        <div class="flex flex-col" x-data="{ password: '' }">
                            <form method="POST" action="{{ route('account.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <div class="flex flex-col">
                                    <label class="mb-4" for="password">パスワード</label>
                                    <x-element.input
                                        name="password"
                                        type="password"
                                        placeholder="パスワードを入力"
                                        required
                                        x-model="password"
                                        class="mb-4"
                                    />
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <x-element.modal-close />
                                    <x-element.button theme="danger" type="submit" ::disabled="password === ''">
                                        削除
                                    </x-element.button>
                                </div>
                            </form>
                        </div>
                    </x-element.modal>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
