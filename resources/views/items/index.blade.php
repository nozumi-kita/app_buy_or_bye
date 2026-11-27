@use('\App\Enums\ItemStatus')

<x-layouts.app>
    <section>
        <x-items.item-totals :$totals />
        <div class="flex justify-between mb-4">
            <div class="flex items-center gap-6">
                <h2 class="text-xl">気になるもの一覧</h2>
                <form method="GET" action="{{ route('items.index') }}">
                    <select
                        class="cursor-pointer border border-line rounded-md px-3 py-2 focus-visible:ring-1 focus-visible:outline-none focus-visible:ring-focus-line"
                        name="status" id="status-filter"
                    >
                        @foreach (ItemStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($selectedStatus === $status)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div>
                <x-element.button href="{{ route('items.create') }}" class="hidden md:block px-3">
                    気になるものを登録
                </x-element.button>
                <a class="fixed bottom-18 right-6 flex justify-center items-center text-fg-on-emphasis size-12 md:hidden rounded-full bg-primary hover:bg-primary-hover z-1 transition duration-300"
                    href="{{ route('items.create') }}" aria-label="気になるものを登録"
                >
                    <x-icon.plus />
                </a>
            </div>
        </div>
        @if (session('success'))
            <x-element.flash class="mb-4">
                {{ session('success') }}
            </x-element.flash>
        @endif
        @foreach (session('awardedMedalNames', []) as $medalName)
            <x-element.flash type="info" class="mb-4">
                <span>実績:「{{ $medalName }}」を達成しました！</span>
            </x-element.flash>
        @endforeach
        @if ($items->isEmpty())
            <p>表示するものがありません</p>
        @else
            <ul class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($items as $item)
                    <li
                        class="flex flex-col relative border border-line rounded-md p-4 hover:bg-surface-hover transition duration-300">
                        <div class="flex justify-between mb-2 items-center gap-2">
                            <h3 class="text-lg truncate">
                                <a class="after:absolute after:inset-0" href="{{ route('items.show', $item) }}">
                                    {{ $item->name }}
                                </a>
                            </h3>
                            <x-element.icon-button
                                :href="route('items.edit', $item)"
                                icon="pencil-square"
                                label="編集"
                                icon-size="size-6"
                            />
                        </div>
                        <div class="flex flex-1 flex-col gap-4">
                            <div>
                                <p class="text-xl font-bold">{{ format_jpy($item->price) }}</p>
                            </div>
                            <div class="flex gap-2">
                                @if ($item->memo)
                                    <div class="truncate">
                                        <p class="text-fg-muted truncate">{{ $item->memo }}</p>
                                    </div>
                                @endif
                                <div class="ml-auto text-fg-muted">
                                    <p>{{ format_jst($item->updated_at, 'Y/m/d') }}</p>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</x-layouts.app>
