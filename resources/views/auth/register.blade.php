<main>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">名前</label>
        <input id="name" name="name" type="text">
        @error('name')
            <p>{{ $message }}</p>
        @enderror
        <label for="email">メールアドレス</label>
        <input id="email" name="email" type="email">
        @error('email')
            <p>{{ $message }}</p>
        @enderror

        <label for="password">パスワード</label>
        <input id="password" name="password" type="password">
        @error('password')
            <p>{{ $message }}</p>
        @enderror
        <label for="password_confirmation">パスワード確認</label>
        <input id="password_confirmation" name="password_confirmation" type="password">
        <button type="submit">登録</button>
    </form>
</main>
