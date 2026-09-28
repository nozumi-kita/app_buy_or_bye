<x-layouts.guest>
    <section class="auth-card">
        <h1 class="auth-title">ユーザー登録</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-field">
                <label class="form-label" for="name">名前</label>
                <input
                    id="name"
                    class="form-input"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    placeholder="山田太郎"
                    autofocus
                    required
                >
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
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
                    autocomplete="new-password"
                    placeholder="8文字以上"
                    required
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="password_confirmation">パスワード確認</label>
                <input
                    id="password_confirmation"
                    class="form-input"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    placeholder="もう一度同じパスワードを入力"
                    required
                >
            </div>
            <button class="btn-primary" type="submit">登録</button>
        </form>
        <div class="auth-footer">
            <a href="{{ route('login') }}">アカウントをお持ちの方はこちら</a>
        </div>
    </section>
</x-layouts.guest>
