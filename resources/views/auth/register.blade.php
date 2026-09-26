<main>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">名前</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
        @error('name')
            <p>{{ $message }}</p>
        @enderror
        <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <label for="password">パスワード</label>
        <input id="password" name="password" type="password" required>
        @error('password')
            <p>{{ $message }}</p>
        @enderror
        <label for="password_confirmation">パスワード確認</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required>
        <button type="submit">登録</button>
    </form>
    <a href="{{ route('login') }}">アカウントをお持ちの方はこちら</a>
</main>
