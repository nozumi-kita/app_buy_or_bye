<header class="header container">
    <h1 class="title-logo">
        <a href="{{ route('items.index') }}">
            <img class="logo-image" src="{{ asset('images/app-logo.svg') }}" alt="Buy or Bye">
        </a>
    </h1>
    <button
        class="hamburger-menu"
        aria-label="メニュー"
        aria-expanded="false"
        aria-controls="global-menu"
    >
        <span class="hamburger-bar"></span>
        <span class="hamburger-bar"></span>
        <span class="hamburger-bar"></span>
    </button>
    <nav class="nav" id="global-menu">
        <ul class="nav-list">
            <li class="nav-item"><a class="nav-link" href="{{ route('items.index') }}">気になるもの</a></li>
            @auth
                <li class="nav-item"><a class="nav-link" href="{{ route('medals') }}">実績</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('settings.index') }}">設定</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout" type="submit" onclick="return confirm('本当にログアウトしてよろしいですか？')">
                            ログアウト
                        </button>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a class="account-create-link" href="{{ route('register') }}">データを引き継いでアカウント作成</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout" type="submit"
                            onclick="return confirm('ゲストログインを終了すると、登録したデータは二度と表示できません。よろしいですか？')"
                        >ゲストログイン終了</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>
</header>
