@use ('App\Enums\ItemStatus')

@props(['totals'])

<section class="totals">
    <div class="totals-main">
        <p class="totals-title purchase-avoided">{{ ItemStatus::PurchaseAvoided->label() }}</p>
        <div class="totals-amounts">
            <div class="totals-amount totals-amount-this-week">
                <span class="totals-amount-label totals-amount-prime">今週</span>
                <div class="totals-amount-results">
                    <span class="totals-amount-count">
                        {{ $totals->thisWeekCount(ItemStatus::PurchaseAvoided) }}件
                    </span>
                    <span
                        class="totals-amount-value">{{ format_jpy($totals->thisWeekAmount(ItemStatus::PurchaseAvoided)) }}
                    </span>
                </div>
            </div>
            <div class="totals-amount totals-amount-all">
                <span class="totals-amount-label totals-amount-prime">累計</span>
                <div class="totals-amount-results">
                    <span class="totals-amount-count">
                        {{ $totals->allTimeCount(ItemStatus::PurchaseAvoided) }}件
                    </span>
                    <span class="totals-amount-value">
                        {{ format_jpy($totals->allTimeAmount(ItemStatus::PurchaseAvoided)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="totals-sub">
        <div class="totals-amount">
            <span class="totals-amount-label">{{ ItemStatus::Pending->label() }}/合計</span>
            <div class="totals-amount-results">
                <span class="totals-amount-count">
                    {{ $totals->allTimeCount(ItemStatus::Pending) }}件
                </span>
                <span class="totals-amount-value">
                    {{ format_jpy($totals->allTimeAmount(ItemStatus::Pending)) }}
                </span>
            </div>
        </div>
        <div class="totals-amount">
            <span class="totals-amount-label">{{ ItemStatus::Purchased->label() }}/今週</span>
            <div class="totals-amount-results">
                <span class="totals-amount-count">
                    {{ $totals->thisWeekCount(ItemStatus::Purchased) }}件
                </span>
                <span class="totals-amount-value">
                    {{ format_jpy($totals->thisWeekAmount(ItemStatus::Purchased)) }}
                </span>
            </div>
        </div>
    </div>
</section>
