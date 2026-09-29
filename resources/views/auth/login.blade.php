<x-layouts.guest>
    <section class="auth-card">
        <h1 class="auth-title">ログイン</h1>
        <form method="POST" action="{{ route('login') }}">
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
            <a href="{{ route('register') }}">アカウントをお持ちでない方はこちら</a>
        </div>
    </section>
</x-layouts.guest>
