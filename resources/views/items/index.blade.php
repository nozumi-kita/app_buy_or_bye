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
                <a class="hidden w-full rounded-3xl bg-primary px-3 py-3 text-fg-on-emphasis transition duration-300 hover:bg-primary-hover md:block"
                    href="{{ route('items.create') }}"
                >気になるものを登録</a>
                <a class="fixed bottom-18 right-6 flex justify-center items-center text-fg-on-emphasis size-12 md:hidden rounded-full bg-primary hover:bg-primary-hover z-1 transition duration-300"
                    href="{{ route('items.create') }}" aria-label="気になるものを登録"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-7"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
            </div>
        </div>
        @if (session('success'))
            <p class="flex justify-center gap-1 rounded-3xl bg-flash-success p-3 mb-4 text-fg-on-emphasis">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>
                {{ session('success') }}
            </p>
        @endif
        @foreach (session('awardedMedalNames', []) as $medalName)
            <p class="flex rounded-3xl gap-1 bg-flash-info justify-center p-3 mb-4 text-fg-on-emphasis">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0"
                    />
                </svg>
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
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-6"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                    />
                                </svg>
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
