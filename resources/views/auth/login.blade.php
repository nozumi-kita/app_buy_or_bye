<x-layout>
    <main>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <p>{{ $message }}</p>
                @enderror
            </div>
            <button type="submit">ログイン</button>
        </form>
        <p>
            <a href="{{ route('register') }}">アカウントをお持ちでない方はこちらから</a>
        </p>
    </main>
</x-layout>
