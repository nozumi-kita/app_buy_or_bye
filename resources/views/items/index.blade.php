<x-layouts.app>
    <section>
        <div class="item-list-header">
            <h2>気になるもの一覧</h2>
            <a class="link-register" href="{{ route('items.create') }}">気になるものを登録</a>
        </div>
        @if (session('success'))
            <p class="flash flash-success">{{ session('success') }}</p>
        @endif
        @if ($items->isEmpty())
            <p>表示するものがありません</p>
        @else
            <ul class="item-list">
                @foreach ($items as $item)
                    <li class="item-card">
                        <div class="item-card-header">
                            <h3 class="item-name">{{ $item->name }}</h3>
                            <span class="status-badge">{{ $item->status->label() }}</span>
                        </div>
                        <dl class="item-details">
                            <div class="item-detail">
                                <dt class="detail-label">価格</dt>
                                <dd class="detail-value">{{ Number::currency($item->price, in: 'JPY') }}</dd>
                            </div>
                            <div class="item-detail">
                                <dt class="detail-label">メモ</dt>
                                <dd class="detail-value memo">{{ $item->memo }}</dd>
                            </div>
                            <div class="item-detail">
                                <dt class="detail-label">更新日時</dt>
                                <dd class="detail-value">{{ $item->updated_at->format('Y/m/d H:i') }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('items.edit', $item) }}">編集</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</x-layouts.app>
