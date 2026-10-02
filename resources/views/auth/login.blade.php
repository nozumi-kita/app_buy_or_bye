<x-layouts.guest>
    <section class="auth-card">
        <h1 class="auth-title">ログイン</h1>
        <form class="form-login" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-field">
                <label class="form-label" for="email">メールアドレス</label>
                <input
                    id="email"
                    class="form-input"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="username"
                    placeholder="user@example.com"
                    autofocus
                    required
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="password">パスワード</label>
                <input
                    id="password"
                    class="form-input"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="パスワードを入力"
                    required
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">ログイン</button>
        </form>
        <div class="auth-footer">
            @if ($guestLoggedIn)
                <p class="auth-guest-notice">
                    <a class="items-link" href="{{ route('items.index') }}">一覧に戻る</a>
                    ゲストログイン中です。データを引き継いで利用を開始する場合、下記よりアカウントを作成してください。
                </p>
            @else
                <form method="POST" action="{{ route('guest-login') }}">
                    @csrf
                    <button class="btn btn-guest-login" type="submit"
                        onclick="return confirm('ゲストログインを開始します。よろしいですか？')"
                    >ゲストログイン</button>
                </form>
            @endif
            <a href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
        </div>
    </section>
</x-layouts.guest>
