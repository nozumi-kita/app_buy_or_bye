<x-layouts.app>
    <section class="settings-card">
        <div class="settings-detail">
            <h2>設定</h2>
            <dl>
                <div class="settings-detail-content">
                    <dt class="settings-detail-label">ユーザー名</dt>
                    <dd class="settings-detail-value">{{ $user->name }}</dd>
                </div>
                <div class="settings-detail-content">
                    <dt class="settings-detail-label">メールアドレス</dt>
                    <dd class="settings-detail-value">{{ $user->email }}</dd>
                </div>
            </dl>
            <form
                class="account-form"
                method="POST"
                action="{{ route('account.destroy') }}"
                onsubmit="return confirm('本当に削除しますか？')"
            >
                @csrf
                @method('DELETE')
                <h3 class="account-form-title">アカウント削除</h3>
                <p>アカウントは一度削除すると復旧できません。</p>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <label class="account-form-label" for="password">パスワード</label>
                <input
                    id="password"
                    class="form-input account-delete"
                    name="password"
                    type="password"
                    placeholder="パスワードを入力"
                    required
                />
                <button class="btn btn-account-delete" type="submit">アカウントを削除する</button>
            </form>
        </div>
    </section>
</x-layouts.app>
