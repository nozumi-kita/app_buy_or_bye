<x-layouts.app>
    <section class="form-card">
        <form method="POST" action="{{ route('items.store') }}">
            @csrf
            <h2>気になるものを登録</h2>
            <div class="form-field">
                <label class="form-label" for="name">品名</label>
                <input
                    id="name"
                    class="form-input"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    autofocus
                    maxlength="255"
                    placeholder="キーボード"
                    required
                >
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="price">価格(¥)</label>
                <input
                    id="price"
                    class="form-input"
                    name="price"
                    type="number"
                    min="1"
                    max="10000000"
                    value="{{ old('price') }}"
                    placeholder="20000"
                    required
                >
                @error('price')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label class="form-label" for="memo">メモ</label>
                <textarea
                    id="memo"
                    class="form-input"
                    name="memo"
                    maxlength="500"
                    rows="3"
                >{{ old('memo') }}</textarea>
                @error('memo')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-actions">
                <a class="link-cancel" href="{{ route('items.index') }}">キャンセル</a>
                <button class="btn btn-primary" type="submit">登録</button>
            </div>
        </form>
    </section>
</x-layouts.app>
