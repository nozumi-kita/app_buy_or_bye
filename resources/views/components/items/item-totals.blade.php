@use ('App\Enums\ItemStatus')

@props(['totals'])

<section class="border rounded-md md:grid md:grid-cols-2 md:divide-x divide-line mb-5 border-line">
    <div class="border-b md:border-b-0 p-4">
        <h2 class="flex text-xl items-center mb-4 gap-1">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="size-6"
            >
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z"
                />
            </svg>
            <span>{{ ItemStatus::PurchaseAvoided->label() }}</span>
        </h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="flex gap-4">
                    <span>今週</span>
                    <span>{{ $totals->thisWeekCount(ItemStatus::PurchaseAvoided) }}件</span>
                </p>
                <p class="text-2xl font-bold break-all">
                    {{ format_jpy($totals->thisWeekAmount(ItemStatus::PurchaseAvoided)) }}</p>
            </div>
            <div>
                <p class="flex gap-4">
                    <span>累計</span>
                    <span>{{ $totals->allTimeCount(ItemStatus::PurchaseAvoided) }}件</span>
                </p>
                <p class="text-2xl font-bold break-all">
                    {{ format_jpy($totals->allTimeAmount(ItemStatus::PurchaseAvoided)) }}</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 divide-x divide-line">
        <div class="p-4">
            <h2 class="flex items-center text-xl mb-4 gap-1">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"
                    />
                </svg>
                <span>{{ ItemStatus::Pending->label() }}</span>
            </h2>
            <p class="flex gap-4">
                <span>合計</span>
                <span>{{ $totals->allTimeCount(ItemStatus::Pending) }}件</span>
            </p>
            <p>
                <span class="text-xl">
                    {{ format_jpy($totals->allTimeAmount(ItemStatus::Pending)) }}
                </span>
            </p>
        </div>
        <div class="p-4">
            <h2 class="flex items-center text-xl mb-4 gap-1">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m9 7.5 3 4.5m0 0 3-4.5M12 12v5.25M15 12H9m6 3H9m12-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>
                <span>{{ ItemStatus::Purchased->label() }}</span>
            </h2>
            <p class="flex gap-4">
                <span>今週</span>
                <span>
                    {{ $totals->thisWeekCount(ItemStatus::Purchased) }}件
                </span>
            </p>
            <p>
                <span class="text-xl">
                    {{ format_jpy($totals->thisWeekAmount(ItemStatus::Purchased)) }}
                </span>
            </p>
        </div>
    </div>
</section>
