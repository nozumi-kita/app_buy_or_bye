<x-layouts.app>
    <section class="item-detail-card">
        <a href="{{ route('items.index') }}">一覧に戻る</a>
        <dl class="item-detail-contents">
            <div class="item-detail-content">
                <dt class="item-detail-label">品名</dt>
                <dd class="item-detail-value">{{ $item->name }}</dd>
            </div>
            <div class="item-detail-content">
                <dt class="item-detail-label">価格</dt>
                <dd class="item-detail-value">{{ Number::currency($item->price, in: 'JPY') }}</dd>
            </div>
            <div class="item-detail-content">
                <dt class="item-detail-label">メモ</dt>
                <dd class="item-detail-value memo">{{ $item->memo }}</dd>
            </div>
            <div class="item-detail-content">
                <dt class="item-detail-label">更新日時</dt>
                <dd class="item-detail-value">{{ $item->updated_at->format('Y/m/d H:i') }}</dd>
            </div>
            <div class="item-detail-content">
                <dt class="item-detail-label">ステータス</dt>
                <dd class="item-detail-value">{{ $item->status->label() }}</dd>
            </div>
        </dl>
        <div class="detail-actions">
            <form method="POST" action="{{ route('items.destroy', $item) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-delete" type="submit" onclick="return confirm('削除します。本当によろしいですか？')">
                    削除
                </button>
            </form>
            <a class="link item-edit-link" href="{{ route('items.edit', $item) }}">編集</a>
        </div>
    </section>
</x-layouts.app>
