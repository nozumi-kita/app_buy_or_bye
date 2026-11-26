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
                    <x-element.navigation-item :href="route('items.index')" icon="home">
                        ホーム
                    </x-element.navigation-item>
                </li>
                @auth
                    <li>
                        <x-element.navigation-item :href="route('medals')" icon="trophy">
                            実績
                        </x-element.navigation-item>
                    </li>
                    <li>
                        <x-element.navigation-item :href="route('settings.index')" icon="cog-8-tooth">
                            設定
                        </x-element.navigation-item>
                    </li>
                    <li>
                        <x-element.modal>
                            <x-slot:trigger>
                                <x-element.navigation-item icon="arrow-right-start-on-rectangle">
                                    ログアウト
                                </x-element.navigation-item>
                            </x-slot:trigger>
                            <p class="mb-2">ログアウトします。本当によろしいですか？</p>
                            <div class="grid grid-cols-2 gap-3">
                                <x-element.modal-close />
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-element.button type="submit">
                                        ログアウト
                                    </x-element.button>
                                </form>
                            </div>
                        </x-element.modal>
                    </li>
                @else
                    <li>
                        <x-element.navigation-item :href="route('register')" icon="user-plus">
                            アカウント作成
                        </x-element.navigation-item>
                    </li>
                    <li>
                        <x-element.modal>
                            <x-slot:trigger>
                                <x-element.navigation-item icon="arrow-right-start-on-rectangle">
                                    ゲスト終了
                                </x-element.navigation-item>
                            </x-slot:trigger>
                            <p class="mb-2">ゲストログインを終了すると、<span class="text-danger">登録したデータは二度と表示できません。</span>よろしいですか？</p>
                            <div class="grid grid-cols-2 gap-3">
                                <x-element.modal-close />
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-element.button type="submit">
                                        ゲスト終了
                                    </x-element.button>
                                </form>
                            </div>
                        </x-element.modal>
                    </li>
                @endauth
            </ul>
        </nav>
    </div>
</header>
