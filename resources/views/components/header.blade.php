<header class="header container">
    <h1 class="logo">Buy or Bye</h1>
    <nav class="nav">
        <ul class="nav-list">
            <li class="nav-item"><a class="nav-link" href="{{ route('items.index') }}">気になるもの</a></li>
            @auth
                <li class="nav-item"><a class="nav-link" href="#">実績</a></li>
                <li class="nav-item"><a class="nav-link" href="#">設定</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout" type="submit">ログアウト</button>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('register') }}">データを引き継いでアカウント作成</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout" type="submit"
                            onclick="return confirm('ゲストログインを終了すると、登録したデータは二度と表示できません。よろしいですか？')"
                        >ゲストログインを終了</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>
</header>
