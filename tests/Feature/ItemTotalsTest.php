<?php

use App\Enums\ItemStatus;
use App\Models\Item;
use App\Models\User;
use App\Support\ItemOwner;
use App\Support\ItemTotals;
use Carbon\CarbonImmutable;
use Tests\TestCase;

describe('ステータスごとの集計', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('一覧画面に集計結果が表示されていること', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 15000,
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 10000,
            'status_changed_at' => now()->subMonth(),
        ]);

        $this->actingAs($this->user)
            ->get(route('items.index'))
            ->assertSeeInOrder([
                '今週', '1件', '¥15,000',
                '累計', '2件', '¥25,000',
            ]);
    });

    test('「見送り」は今週分と累計の両方が集計されていること', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 15000,
            'status_changed_at' => now(),
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 20000,
            'status_changed_at' => now(),
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 30000,
            'status_changed_at' => now()->subMonth(),
        ]);

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(2);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(35000);
        expect($totals->allTimeCount(ItemStatus::PurchaseAvoided))->toBe(3);
        expect($totals->allTimeAmount(ItemStatus::PurchaseAvoided))->toBe(65000);
    });

    test('「保留中」は累計が集計されていること', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 15000,
            'status_changed_at' => now(),
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 30000,
            'status_changed_at' => now()->subMonth(),
        ]);

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->allTimeCount(ItemStatus::Pending))->toBe(2);
        expect($totals->allTimeAmount(ItemStatus::Pending))->toBe(45000);
    });

    test('「購入済」は今週分のみ集計され、先週分は含まれないこと', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Purchased,
            'price' => 15000,
            'status_changed_at' => now(),
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Purchased,
            'price' => 30000,
            'status_changed_at' => now()->subWeek(),
        ]);

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::Purchased))->toBe(1);
        expect($totals->thisWeekAmount(ItemStatus::Purchased))->toBe(15000);
    });

    test('集計結果に他のユーザーのものが含まれていないこと', function () {
        /** @var TestCase $this */
        Item::factory()->create([
            'price' => 5000,
            'status' => ItemStatus::PurchaseAvoided,
        ]);

        Item::factory()->guest('guest-session_id')->create([
            'price' => 8000,
            'status' => ItemStatus::PurchaseAvoided,
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 15000,
        ]);

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(1);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(15000);
        expect($totals->allTimeCount(ItemStatus::PurchaseAvoided))->toBe(1);
        expect($totals->allTimeAmount(ItemStatus::PurchaseAvoided))->toBe(15000);
    });

    test('1件も登録していない場合は、すべて0になること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(0);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(0);
        expect($totals->allTimeCount(ItemStatus::PurchaseAvoided))->toBe(0);
        expect($totals->allTimeAmount(ItemStatus::PurchaseAvoided))->toBe(0);
        expect($totals->allTimeCount(ItemStatus::Pending))->toBe(0);
        expect($totals->allTimeAmount(ItemStatus::Pending))->toBe(0);
        expect($totals->thisWeekCount(ItemStatus::Purchased))->toBe(0);
        expect($totals->thisWeekAmount(ItemStatus::Purchased))->toBe(0);
    });

    test('日本時間で月曜0時過ぎに追加したものの金額が今週の合計に含まれること', function () {
        /** @var TestCase $this */
        // 2026年9月14日は月曜日
        $this->travelTo(CarbonImmutable::parse('2026-09-14 00:00:01', 'Asia/Tokyo'));

        Item::factory()->for($this->user)->create([
            'price' => 10000,
            'status' => ItemStatus::PurchaseAvoided,
            'status_changed_at' => now(),
        ]);

        // 2026年9月16日は水曜日
        $this->travelTo(CarbonImmutable::parse('2026-09-16 12:00:00', 'Asia/Tokyo'));

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(1);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(10000);
    });

    test('日本時間で日曜深夜に追加したものの金額は、今週の合計に含まれないこと', function () {
        /** @var TestCase $this */
        // 2026年9月13日は日曜日
        $this->travelTo(CarbonImmutable::parse('2026-09-13 23:59:59', 'Asia/Tokyo'));

        Item::factory()->for($this->user)->create([
            'price' => 10000,
            'status' => ItemStatus::PurchaseAvoided,
            'status_changed_at' => now(),
        ]);

        // 2026年9月14日は月曜日
        $this->travelTo(CarbonImmutable::parse('2026-09-14 00:00:00', 'Asia/Tokyo'));

        $this->actingAs($this->user);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(0);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(0);
    });

    test('ゲストユーザーは自分の集計結果のみが集計されること', function () {
        /** @var TestCase $this */
        $hashedSessionId = startGuestSession();

        Item::factory()->guest($hashedSessionId)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 15000,
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 20000,
        ]);

        $totals = ItemTotals::for(ItemOwner::current());

        expect($totals->thisWeekCount(ItemStatus::PurchaseAvoided))->toBe(1);
        expect($totals->thisWeekAmount(ItemStatus::PurchaseAvoided))->toBe(15000);
    });
});
