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
            <p class="flex justify-center gap-1 rounded-3xl bg-flash-success p-3 mb-4 text-fg-on-emphasis">
                <x-icon.check-circle />
                {{ session('success') }}
            </p>
        @endif
        @foreach (session('awardedMedalNames', []) as $medalName)
            <p class="flex rounded-3xl gap-1 bg-flash-info justify-center p-3 mb-4 text-fg-on-emphasis">
                <x-icon.trophy />
                <span>実績:「{{ $medalName }}」を達成しました！</span>
            </p>
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
                            <a
                                href="{{ route('items.edit', $item) }}"
                                class="flex justify-center items-center relative rounded size-9 hover:bg-highlight transition duration-300 shrink-0"
                                aria-label="編集"
                                x-data="{ open: false }"
                                @mouseenter="open = true"
                                @mouseleave="open = false"
                            >
                                <x-icon.pencil-square size="size-6" />
                                <span
                                    class="pointer-events-none text-sm top-full mt-1 text-fg-on-emphasis bg-surface rounded px-2 py-1 absolute whitespace-nowrap left-1/2 -translate-x-1/2"
                                    x-show="open"
                                    x-transition.duration.200ms
                                    x-cloak
                                >編集
                                </span>
                            </a>
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
