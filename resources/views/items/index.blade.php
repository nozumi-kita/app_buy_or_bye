<x-layouts.app>
    <section>
        <x-items.item-price-totals />
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
                            <h3 class="item-name">
                                <a class="item-card-link" href="{{ route('items.show', $item) }}">
                                    {{ $item->name }}
                                </a>
                            </h3>
                            <span class="status-badge">{{ $item->status->label() }}</span>
                        </div>
                        <dl class="item-contents">
                            <div class="item-content">
                                <dt class="item-content-label">価格</dt>
                                <dd class="item-content-value">{{ format_jpy($item->price) }}</dd>
                            </div>
                            <div class="item-content">
                                <dt class="item-content-label">メモ</dt>
                                <dd class="item-content-value memo">{{ $item->memo }}</dd>
                            </div>
                            <div class="item-content">
                                <dt class="item-content-label">更新日時</dt>
                                <dd class="item-content-value">{{ format_jst($item->updated_at) }}</dd>
                            </div>
                        </dl>
                        <div class="edit-link">
                            <a class="link item-edit-link" href="{{ route('items.edit', $item) }}">編集</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</x-layouts.app>
