<?php

use App\Enums\ItemStatus;
use App\Enums\MedalConditionType;
use App\Models\Item;
use App\Models\Medal;
use App\Models\User;
use App\Support\MedalAwarder;
use Carbon\CarbonImmutable;
use Database\Seeders\MedalSeeder;
use Tests\TestCase;

describe('実績達成', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('実績画面にアクセスできること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->get(route('medals'))
            ->assertOk()
            ->assertViewIs('medals.index');
    });

    test('しきい値に達した実績のみメダルが付与されること', function (MedalConditionType $type, int $threshold, bool $expected) {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->count(2)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 5000,
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Purchased,
            'price' => 50000,
        ]);

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 50000,
        ]);

        $medal = Medal::factory()->create([
            'condition_type' => $type,
            'threshold' => $threshold,
        ]);

        $awarded = MedalAwarder::awardFor($this->user);

        expect($awarded->pluck('id')->all())->toBe($expected ? [$medal->id] : []);
    })->with([
        '登録件数4件/しきい値4件 → メダル付与' => [MedalConditionType::ItemCount, 4, true],
        '登録件数4件/しきい値5件 → メダル付与なし' => [MedalConditionType::ItemCount, 5, false],
        '購入回避2件/しきい値2件 → メダル付与' => [MedalConditionType::AvoidedCount, 2, true],
        '購入回避2件/しきい値3件 → メダル付与なし' => [MedalConditionType::AvoidedCount, 3, false],
        '購入回避額10,000円/しきい値10,000円 → メダル付与' => [MedalConditionType::AvoidedAmount, 10000, true],
        '購入回避額10,000/しきい値10,001円 → メダル付与なし' => [MedalConditionType::AvoidedAmount, 10001, false],
    ]);

    test('獲得済みのメダルは二重に付与されないこと', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 1000,
        ]);

        Medal::factory()->create([
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        MedalAwarder::awardFor($this->user);
        $second = MedalAwarder::awardFor($this->user);

        expect($second)->toBeEmpty();
        $this->assertDatabaseCount('medal_user', 1);
    });

    test('複数条件を満たした場合、同時に複数のメダルが付与されること', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 10000,
        ]);

        $medalFirst = Medal::factory()->create([
            'condition_type' => MedalConditionType::AvoidedAmount,
            'threshold' => 5000,
        ]);

        $medalSecond = Medal::factory()->create([
            'condition_type' => MedalConditionType::AvoidedAmount,
            'threshold' => 10000,
        ]);

        MedalAwarder::awardFor($this->user);

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medalFirst->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medalSecond->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseCount('medal_user', 2);
    });

    test('メダル付与後に気になるものを削除しても、メダルが残ること', function () {
        /** @var TestCase $this */
        $item = Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 1000,
        ]);

        $medal = Medal::factory()->create([
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        MedalAwarder::awardFor($this->user);

        $item->delete();

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseCount('medal_user', 1);

        $this->actingAs($this->user)
            ->get(route('medals'))
            ->assertOk()
            ->assertSee('達成日:')
            ->assertDontSee('0件/1件');
    });

    test('他のユーザーやゲストのデータは実績に反映されないこと', function () {
        /** @var TestCase $this */
        $otherUser = User::factory()->create();

        Item::factory()->for($this->user)->create();

        Item::factory()->for($otherUser)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 10000,
        ]);

        Item::factory()->guest('guest_session_id')->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 10000,
        ]);

        $medalCount = Medal::factory()->create([
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        $medalAmount = Medal::factory()->create([
            'condition_type' => MedalConditionType::AvoidedAmount,
            'threshold' => 5000,
        ]);

        MedalAwarder::awardFor($this->user);

        $this->assertDatabaseMissing('medal_user', [
            'medal_id' => $medalAmount->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medalCount->id,
            'user_id' => $this->user->id,
        ]);
    });

    test('acquired_atがUTC時間で保存されること', function () {
        /** @var TestCase $this */
        $this->travelTo(CarbonImmutable::parse('2026-09-20 8:30:00', 'Asia/Tokyo'));

        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::Pending,
            'price' => 1000,
        ]);

        $medal = Medal::factory()->create([
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        MedalAwarder::awardFor($this->user);

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
            'acquired_at' => '2026-09-19 23:30:00+00',
        ]);
    });

    test('条件を満たす実績がない場合、メダルが付与されないこと', function () {
        /** @var TestCase $this */
        Item::factory()->for($this->user)->create([
            'status' => ItemStatus::PurchaseAvoided,
            'price' => 5000,
        ]);

        $medal = Medal::factory()->create([
            'condition_type' => MedalConditionType::AvoidedAmount,
            'threshold' => 10000,
        ]);

        MedalAwarder::awardFor($this->user);

        $this->assertDatabaseMissing('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertDatabaseEmpty('medal_user');
    });

    test('ステータスを購入回避に変更して条件を満たした場合、メダルが付与され、一覧画面にフラッシュメッセージが表示されること', function () {
        /** @var TestCase $this */
        $medal = Medal::factory()->create([
            'name' => 'テストメダル',
            'condition_type' => MedalConditionType::AvoidedCount,
            'threshold' => 1,
        ]);

        $item = Item::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->followingRedirects()
            ->put(route('items.update', $item), validItemDataUpdate([
                'status' => ItemStatus::PurchaseAvoided->value,
            ]))
            ->assertOk()
            ->assertSee('実績:「テストメダル」を達成しました！');

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
        ]);
    });

    test('気になるものを登録すると、登録回数に応じたメダルが付与されること', function () {
        /** @var TestCase $this */
        $medal = Medal::factory()->create([
            'name' => 'テストメダル',
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        $this->actingAs($this->user)
            ->followingRedirects()
            ->post(route('items.store'), validItemData())
            ->assertSee('実績:「テストメダル」を達成しました！');

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
        ]);
    });

    test('メダルが付与の条件を満たさない場合、フラッシュメッセージは表示されないこと', function () {
        /** @var TestCase $this */
        Medal::factory()->create([
            'name' => 'テストメダル',
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 3,
        ]);

        $this->actingAs($this->user)
            ->followingRedirects()
            ->post(route('items.store'), validItemData())
            ->assertOk()
            ->assertDontSee('class="flash flash-award"', escape: false);
    });

    test('ゲストが登録・更新してもメダルの付与はさせないこと', function () {
        /** @var TestCase $this */
        startGuestSession();

        $medalItemCount = Medal::factory()->create([
            'name' => '登録回数テスト',
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        $medalAvoidedCount = Medal::factory()->create([
            'name' => '回避回数テスト',
            'condition_type' => MedalConditionType::AvoidedCount,
            'threshold' => 1,
        ]);

        $this->followingRedirects()
            ->post(route('items.store'), validItemData())
            ->assertOk()
            ->assertDontSee('実績:「登録回数テスト」を達成しました！');

        $this->assertDatabaseMissing('medal_user', [
            'medal_id' => $medalItemCount->id,
        ]);

        $item = Item::sole();

        $this->followingRedirects()
            ->put(route('items.update', $item), validItemDataUpdate([
                'status' => ItemStatus::PurchaseAvoided->value,
            ]))->assertOk()
            ->assertDontSee('実績:「回避回数テスト」を達成しました！');

        $this->assertDatabaseMissing('medal_user', [
            'medal_id' => $medalAvoidedCount->id,
        ]);
    });

    test('ゲスト→会員の引き継ぎ時、条件を満たしたメダルが付与されること', function () {
        /** @var TestCase $this */
        $hashedSessionId = startGuestSession();

        Item::factory()->guest($hashedSessionId)->create();

        $medal = Medal::factory()->create([
            'name' => '引き継ぎメダル',
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        $this->followingRedirects()
            ->post(route('register'), validRegistrationData([
                'name' => 'テスト引き継ぎユーザー',
            ]))->assertSee('実績:「引き継ぎメダル」を達成しました！');

        $user = User::where('name', 'テスト引き継ぎユーザー')->sole();

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $user->id,
        ]);
    });

    test('全てのメダルが表示され、達成済なら達成日が、未達成なら進捗状況が表示されること', function () {
        /** @var TestCase $this */
        $acquired = Medal::factory()->create([
            'name' => '達成済メダル',
            'icon_key' => 'avoided_count_1',
        ]);

        Medal::factory()->create([
            'name' => '未達成メダル',
            'condition_type' => MedalConditionType::AvoidedCount,
            'threshold' => 5,
        ]);

        $this->user->medals()->attach($acquired->id, [
            'acquired_at' => CarbonImmutable::parse('2026-09-20 9:30:00', 'Asia/Tokyo'),
        ]);

        Item::factory()->for($this->user)->count(3)->create([
            'status' => ItemStatus::PurchaseAvoided,
        ]);

        $this->actingAs($this->user)
            ->get(route('medals'))
            ->assertOk()
            ->assertSee([
                '達成済メダル', '達成日: 2026/09/20',
                '未達成メダル', '3件/5件', 'avoided_count_1.svg',
            ]);
    });

    test('メダルがdisplay_orderの順番に並ぶこと', function () {
        /** @var TestCase $this */
        Medal::factory()->create([
            'name' => 'メダル3',
            'display_order' => 3,
        ]);

        Medal::factory()->create([
            'name' => 'メダル1',
            'display_order' => 1,
        ]);

        Medal::factory()->create([
            'name' => 'メダル2',
            'display_order' => 2,
        ]);

        $this->actingAs($this->user)
            ->get(route('medals'))
            ->assertOk()
            ->assertSeeInOrder(['メダル1', 'メダル2', 'メダル3']);
    });

    test('他のユーザーが達成した実績は、反映されないこと', function () {
        /** @var TestCase $this */
        $otherUser = User::factory()->create();

        Item::factory()->for($otherUser)->create();

        $otherAcquired = Medal::factory()->create([
            'name' => '他ユーザーのメダル',
            'condition_type' => MedalConditionType::ItemCount,
            'threshold' => 1,
        ]);

        $otherUser->medals()->attach($otherAcquired->id, [
            'acquired_at' => CarbonImmutable::parse('2026-09-20 9:30:00', 'Asia/Tokyo'),
        ]);

        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $otherAcquired->id,
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($this->user)
            ->get(route('medals'))
            ->assertOk()
            ->assertSee(['他ユーザーのメダル', '0件/1件'])
            ->assertDontSee(['達成日: 2026/09/20']);
    });

    test('未認証・ゲストユーザーはログイン画面にリダイレクトされること', function () {
        /** @var TestCase $this */
        $this->get(route('medals'))
            ->assertRedirect(route('login'));

        startGuestSession();

        $this->get(route('medals'))
            ->assertRedirect(route('login'));
    });

    describe('MedalSeeder', function () {
        test('2回実行してもメダルが重複しないこと', function () {
            /** @var TestCase $this */
            $this->seed(MedalSeeder::class);
            $count = Medal::count();

            $this->seed(MedalSeeder::class);

            expect(Medal::count())->toBe($count)
                ->and($count)->toBeGreaterThan(0);
        });

        test('登録したメダルのicon_keyに対応する画像がすべて存在すること', function () {
            /** @var TestCase $this */
            $this->seed(MedalSeeder::class);

            Medal::all()->each(
                fn (Medal $medal) => expect(public_path("images/medals/{$medal->icon_key}.svg"))->toBeFile()
            );
        });
    });
});
