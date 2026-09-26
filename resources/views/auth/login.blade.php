<main>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password">
        <button type="submit">ログイン</button>
    </form>
</main>
