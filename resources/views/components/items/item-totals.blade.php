@use ('App\Enums\ItemStatus')

@props(['totals'])

<section class="border rounded-md md:grid md:grid-cols-2 md:divide-x divide-line mb-5 border-line">
    <div class="border-b md:border-b-0 p-4">
        <h2 class="flex text-xl items-center mb-4 gap-1">
            <x-icon.hand-thumb-up />
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
                <x-icon.bookmark />
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
                <x-icon.currency-yen />
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
