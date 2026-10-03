@use ('App\Enums\ItemStatus')

@props(['totals'])

<section class="totals">
    <div class="totals-main">
        <p class="totals-title purchase-avoided">{{ ItemStatus::PurchaseAvoided->label() }}</p>
        <div class="totals-amounts">
            <div class="totals-amount totals-amount-this-week">
                <p class="totals-amount-label totals-amount-prime">今週</p>
                <div class="totals-amount-results">
                    <p class="totals-amount-count">
                        {{ $totals->thisWeekCount(ItemStatus::PurchaseAvoided) }}件
                    </p>
                    <p class="totals-amount-value">
                        {{ format_jpy($totals->thisWeekAmount(ItemStatus::PurchaseAvoided)) }}
                    </p>
                </div>
            </div>
            <div class="totals-amount totals-amount-all">
                <p class="totals-amount-label totals-amount-prime">累計</p>
                <div class="totals-amount-results">
                    <p class="totals-amount-count">
                        {{ $totals->allTimeCount(ItemStatus::PurchaseAvoided) }}件
                    </p>
                    <p class="totals-amount-value">
                        {{ format_jpy($totals->allTimeAmount(ItemStatus::PurchaseAvoided)) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="totals-sub">
        <div class="totals-amount">
            <p class="totals-amount-label">{{ ItemStatus::Pending->label() }}/合計</p>
            <div class="totals-amount-results">
                <p class="totals-amount-count">
                    {{ $totals->allTimeCount(ItemStatus::Pending) }}件
                </p>
                <p class="totals-amount-value">
                    {{ format_jpy($totals->allTimeAmount(ItemStatus::Pending)) }}
                </p>
            </div>
        </div>
        <div class="totals-amount">
            <p class="totals-amount-label">{{ ItemStatus::Purchased->label() }}/今週</p>
            <div class="totals-amount-results">
                <p class="totals-amount-count">
                    {{ $totals->thisWeekCount(ItemStatus::Purchased) }}件
                </p>
                <p class="totals-amount-value">
                    {{ format_jpy($totals->thisWeekAmount(ItemStatus::Purchased)) }}
                </p>
            </div>
        </div>
    </div>
</section>
