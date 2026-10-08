<x-layouts.app>
    <section class="form-card">
        <form method="POST" action="{{ route('account.update') }}">
            <h2>設定編集</h2>
            @csrf
            @method('PUT')
            <div class="form-field">
                <label class="form-label" for="name">ユーザー名</label>
                <input
                    class="form-input"
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                >
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="email">メールアドレス</label>
                <input
                    class="form-input"
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                >
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="password">パスワード</label>
                <input
                    class="form-input"
                    name="password"
                    id="password"
                    type="password"
                    placeholder="パスワードを入力してください"
                    required
                >
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-actions">
                <a class="link-cancel" href="{{ route('settings.index') }}">キャンセル</a>
                <button class="btn btn-primary" type="submit">更新</button>
            </div>
        </form>
    </section>
</x-layouts.app>
