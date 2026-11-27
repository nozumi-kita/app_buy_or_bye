<x-layouts.app>
    <section>
        <div class="text-center text-2xl border-b border-line pb-4 mb-4">
            <div class="grid grid-cols-[1fr_auto_1fr] mx-auto max-w-md">
                <x-element.back-link :href="route('items.index')" />
                <h2>詳細</h2>
            </div>
        </div>
        <div class="mx-auto max-w-md border border-line rounded-md p-4">
            <dl class="flex flex-col md:gap-10 gap-7 mb-6">
                <div class="md:flex md:items-baseline gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">品名</dt>
                    <dd class="break-all text-xl">{{ $item->name }}</dd>
                </div>
                <div class="md:flex md:items-baseline md:gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">価格</dt>
                    <dd class="break-all text-xl">{{ format_jpy($item->price) }}</dd>
                </div>
                <div class="md:flex md:items-baseline md:gap-16">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">メモ</dt>
                    <dd class="break-all">{{ $item->memo }}</dd>
                </div>
                <div class="md:flex md:gap-8">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">更新日時</dt>
                    <dd class="break-all">{{ format_jst($item->updated_at) }}</dd>
                </div>
                <div class="md:flex gap-4">
                    <dt class="shrink-0 text-fg-muted mb-2 md:mb-0">ステータス</dt>
                    <dd class="break-all">{{ $item->status->label() }}</dd>
                </div>
            </dl>
            <div class="flex justify-between items-center">
                <x-element.modal>
                    <x-slot:trigger>
                        <x-element.icon-button icon="trash" label="削除" />
                    </x-slot:trigger>
                    <p>削除します。よろしいですか？</p>
                    <div class="grid grid-cols-2 gap-3">
                        <x-element.modal-close />
                        <form method="POST" action="{{ route('items.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <x-element.button theme="danger" type="submit">
                                削除
                            </x-element.button>
                        </form>
                    </div>
                </x-element.modal>
                <x-element.icon-button :href="route('items.edit', $item)" icon="pencil-square" label="編集" />
            </div>
        </div>
    </section>
</x-layouts.app>
