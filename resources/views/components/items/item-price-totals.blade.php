@use ('App\Enums\ItemStatus')

<section class="totals">
    <div class="totals-main">
        <p class="totals-title purchase-avoided">{{ ItemStatus::PurchaseAvoided->label() }}</p>
        <div class="totals-amounts">
            <div class="totals-amount totals-amount-this-week">
                <span class="totals-amount-label totals-amount-prime">今週</span>
                <div class="totals-amount-results">
                    <span class="totals-amount-count">1000件</span>
                    <span class="totals-amount-value">{{ format_jpy(10000) }}</span>
                </div>
            </div>
            <div class="totals-amount totals-amount-all">
                <span class="totals-amount-label totals-amount-prime">累計</span>
                <div class="totals-amount-results">
                    <span class="totals-amount-count">100件</span>
                    <span class="totals-amount-value">{{ format_jpy(10000) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="totals-sub">
        <div class="totals-amount">
            <span class="totals-amount-label">{{ ItemStatus::Pending->label() }}/合計</span>
            <div class="totals-amount-results">
                <span class="totals-amount-count">1000件</span>
                <span class="totals-amount-value">{{ format_jpy(11111) }}</span>
            </div>
        </div>
        <div class="totals-amount">
            <span class="totals-amount-label">{{ ItemStatus::Purchased->label() }}/今週</span>
            <div class="totals-amount-results">
                <span class="totals-amount-count">10000件</span>
                <span class="totals-amount-value">{{ format_jpy(11111) }}</span>
            </div>
        </div>
    </div>
</section>
