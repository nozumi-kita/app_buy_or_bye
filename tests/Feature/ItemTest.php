<?php

use App\Enums\ItemStatus;
use App\Models\Item;
use App\Models\User;
use Tests\TestCase;

describe('ログインユーザー', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    describe('一覧表示', function () {
        test('自分の気になるものが一覧に表示されること', function () {
            /** @var TestCase $this */
            Item::factory()->for($this->user)
                ->create(['name' => '私のキーボード']);

            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertViewIs('items.index')
                ->assertSee('私のキーボード');
        });

        test('他のユーザーの気になるものが一覧に表示されないこと', function () {
            /** @var TestCase $this */
            Item::factory()->create(['name' => '他者のパソコン']);

            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertDontSee('他者のパソコン');
        });

        test('ゲストユーザーの気になるものが一覧に表示されないこと', function () {
            /** @var TestCase $this */
            Item::factory()->guest('guest-session-id')->create([
                'name' => 'ゲストユーザーのアイテム',
            ]);

            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertDontSee('ゲストユーザーのアイテム');

        });

        test('気になるものを1件も登録していない場合、「表示するものがありません」と表示されること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertSee('表示するものがありません');
        });

        test('更新日時が新しい順に並んでいること', function () {
            /** @var TestCase $this */
            Item::factory()->for($this->user)
                ->create([
                    'name' => 'あとから更新',
                    'created_at' => now()->subSeconds(20),
                    'updated_at' => now(),
                ]);

            Item::factory()->for($this->user)
                ->create([
                    'name' => '先に更新',
                    'created_at' => now()->subSeconds(10),
                    'updated_at' => now()->subSeconds(10),
                ]);

            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertSeeInOrder(['あとから更新', '先に更新']);
        });
    });

    describe('新規登録画面', function () {
        test('登録画面が表示されること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->get(route('items.create'))
                ->assertOk()
                ->assertViewIs('items.create');
        });
    });

    describe('新規登録', function () {
        test('気になるものを登録して、一覧画面にリダイレクトされること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->from(route('items.create'))
                ->post(route('items.store'), validItemData())
                ->assertRedirect(route('items.index'))
                ->assertSessionHas('success', '登録が完了しました');

            $this->assertDatabaseHas(Item::class, [
                'user_id' => $this->user->id,
                'name' => 'テスト',
                'price' => 10000,
                'memo' => 'テストメモ',
            ]);
        });

        test('各項目が登録できること', function (array $overrides) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->post(route('items.store'), validItemData($overrides))
                ->assertRedirect(route('items.index'));

            $this->assertDatabaseHas(Item::class, $overrides);
        })->with([
            '品名が1文字' => [['name' => 'a']],
            '品名が255文字' => [['name' => str_repeat('a', 255)]],
            '金額が1円' => [['price' => 1]],
            '金額が10,000,000円' => [['price' => 10000000]],
            'メモが500文字' => [['memo' => str_repeat('a', 500)]],
        ]);

        test('メモが空欄でも登録できること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->post(route('items.store'), validItemData([
                    'memo' => '',
                ]))->assertRedirect(route('items.index'));

            $this->assertDatabaseHas(Item::class, [
                'memo' => null,
            ]);
        });

        test('登録時、statusはpending, status_changed_atは現在時刻となること', function () {
            /** @var TestCase $this */
            $this->freezeTime();

            $this->actingAs($this->user)
                ->post(route('items.store'), validItemData([
                    'status' => ItemStatus::Purchased->value,
                ]));

            $item = Item::sole();

            expect($item->status)->toBe(ItemStatus::Pending)
                ->and($item->status_changed_at->isSameSecond(now()))->toBeTrue();
        });

        test('各項目が空・不正な値の場合、登録できないこと', function (array $overrides, array $errors) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->post(route('items.store'), validItemData($overrides))
                ->assertInvalid($errors);

            $this->assertDatabaseCount(Item::class, 0);
        })->with([
            '品名が空' => [['name' => ''], ['name']],
            '品名が256文字以上' => [['name' => str_repeat('a', 256)], ['name']],
            '価格が空' => [['price' => ''], ['price']],
            '価格が0' => [['price' => 0], ['price']],
            '価格が整数でない' => [['price' => 1500.1], ['price']],
            '価格がマイナス' => [['price' => -1000], ['price']],
            '価格が数値でない' => [['price' => '文字列'], ['price']],
            '価格が10,000,000を超える' => [['price' => 10000001], ['price']],
            'メモが500文字を超える' => [['memo' => str_repeat('a', 501)], ['memo']],
        ]);
    });

    describe('詳細表示', function () {
        test('自分の気になるものの詳細が表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->for($this->user)->create(['name' => '私のパソコン']);

            $this->actingAs($this->user)
                ->get(route('items.show', $item))
                ->assertOk()
                ->assertViewIs('items.show')
                ->assertViewHas('item', $item)
                ->assertSee('私のパソコン');
        });

        test('他のユーザーの気になるものは閲覧できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->create();

            $this->actingAs($this->user)
                ->get(route('items.show', $item))
                ->assertNotFound();
        });

        test('ゲストユーザーの気になるものは閲覧できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest('guest-session-id')->create([
                'name' => 'ゲストユーザーのアイテム',
            ]);

            $this->actingAs($this->user)
                ->get(route('items.show', $item))
                ->assertNotFound();
        });

        test('存在しない気になるものを閲覧しようとした場合、404エラーとなること', function ($value) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->get(route('items.show', $value))
                ->assertNotFound();
        })->with([
            'パラメータが存在しない数字である' => [10000],
            'パラメータに文字列が入っている' => ['string'],
        ]);
    });

    describe('編集画面', function () {
        test('自分の気になるものが表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->for($this->user)->create([
                'name' => 'マイパソコン',
                'price' => '15000',
                'memo' => 'テストです',
            ]);

            $this->actingAs($this->user)
                ->get(route('items.edit', $item))
                ->assertOk()
                ->assertViewIs('items.edit')
                ->assertViewHas('item', $item)
                ->assertSee([
                    'マイパソコン',
                    '15000',
                    'テストです',
                ]);
        });

        test('他のユーザーの気になるものは閲覧できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->create();

            $this->actingAs($this->user)
                ->get(route('items.edit', $item))
                ->assertNotFound();
        });

        test('ゲストユーザーの気になるものは閲覧できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest('guest-session-id')->create([
                'name' => 'ゲストユーザーのアイテム',
            ]);

            $this->actingAs($this->user)
                ->get(route('items.edit', $item))
                ->assertNotFound();
        });

        test('存在しない気になるものを閲覧しようとした場合、404エラーとなること', function ($value) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->get(route('items.edit', $value))
                ->assertNotFound();
        })->with([
            'パラメータが存在しない数字である' => [10000],
            'パラメータに文字列が入っている' => ['string'],
        ]);
    });

    describe('更新', function () {
        beforeEach(function () {
            $this->item = Item::factory()->for($this->user)->create([
                'memo' => 'テストです',
                'status' => ItemStatus::Pending,
                'status_changed_at' => now()->subDay(),
            ]);
        });

        test('気になるものを更新し、一覧画面にリダイレクトされること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->from(route('items.edit', $this->item))
                ->put(route('items.update', $this->item), validItemDataUpdate([
                    'name' => '更新後',
                ]))->assertRedirect(route('items.index'))
                ->assertSessionHas('success', '更新が完了しました');

            $this->assertDatabaseHas(Item::class, [
                'id' => $this->item->id,
                'name' => '更新後',
            ]);
        });

        test('各項目が更新できること', function (array $overrides) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->put(route('items.update', $this->item), validItemDataUpdate($overrides))
                ->assertRedirect(route('items.index'));

            $this->assertDatabaseHas(Item::class, array_merge($overrides, ['id' => $this->item->id]));
        })->with([
            '品名が1文字' => [['name' => 'a']],
            '品名が255文字' => [['name' => str_repeat('a', 255)]],
            '金額が1円' => [['price' => 1]],
            '金額が10,000,000円' => [['price' => 10000000]],
            'メモが500文字' => [['memo' => str_repeat('a', 500)]],
            'ステータスが「購入済」' => [['status' => ItemStatus::Purchased->value]],
            'ステータスが「見送り」' => [['status' => ItemStatus::PurchaseAvoided->value]],
        ]);

        test('メモが空欄でも更新できること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->put(route('items.update', $this->item), validItemDataUpdate([
                    'memo' => '',
                ]))
                ->assertRedirect(route('items.index'));
            $this->assertDatabaseHas(Item::class, [
                'id' => $this->item->id,
                'memo' => null,
            ]);
        });

        test('ステータスを変更して更新すると、status_changed_atが現在時刻に更新されること', function () {
            /** @var TestCase $this */
            $this->freezeTime();

            $this->actingAs($this->user)
                ->put(route('items.update', $this->item), validItemDataUpdate([
                    'status' => ItemStatus::Purchased->value,
                ]));

            $this->item->refresh();

            expect($this->item->status)->toBe(ItemStatus::Purchased)
                ->and($this->item->status_changed_at->isSameSecond(now()))->toBeTrue();
        });

        test('ステータスを変更しなければstatus_changed_atが変わらないこと', function () {
            /** @var TestCase $this */
            $pastTime = $this->item->status_changed_at;

            $this->actingAs($this->user)
                ->put(route('items.update', $this->item), validItemDataUpdate([
                    'name' => '名前だけ変更',
                ]));

            $this->item->refresh();

            expect($this->item->name)->toBe('名前だけ変更')
                ->and($this->item->status_changed_at->isSameSecond($pastTime))->toBeTrue();
        });

        test('各項目が空・不正な値の場合、更新できないこと', function (array $overrides, array $errors) {
            /** @var TestCase $this */
            $before = $this->item->fresh()->getAttributes();

            $this->actingAs($this->user)
                ->put(route('items.update', $this->item), validItemDataUpdate($overrides))
                ->assertInvalid($errors);

            expect($this->item->fresh()->getAttributes())->toBe($before);
        })->with([
            '品名が空' => [['name' => ''], ['name']],
            '品名が256文字以上' => [['name' => str_repeat('a', 256)], ['name']],
            '価格が空' => [['price' => ''], ['price']],
            '価格が0' => [['price' => 0], ['price']],
            '価格が整数でない' => [['price' => 1500.1], ['price']],
            '価格がマイナス' => [['price' => -1000], ['price']],
            '価格が数値でない' => [['price' => 'string'], ['price']],
            '価格が10,000,000を超える' => [['price' => 10000001], ['price']],
            'メモが500文字を超える' => [['memo' => str_repeat('a', 501)], ['memo']],
            'ステータスが空' => [['status' => ''], ['status']],
            'ステータスにEnumで指定した値以外を入れている' => [['status' => 'invalid'], ['status']],
        ]);

        test('他のユーザーの気になるものは更新できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->create(['name' => '他人のパソコン']);

            $this->actingAs($this->user)
                ->put(route('items.update', $item), validItemDataUpdate([
                    'name' => '私のパソコン',
                ]))
                ->assertNotFound();

            $item->refresh();

            expect($item->name)->toBe('他人のパソコン');
        });

        test('ゲストユーザーの気になるものは更新できず、404エラーとなること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest('guest-session-id')->create([
                'name' => 'ゲストユーザーのアイテム',
            ]);

            $this->actingAs($this->user)
                ->put(route('items.update', $item), validItemDataUpdate([
                    'name' => '私のアイテム',
                ]))
                ->assertNotFound();

            $item->refresh();

            expect($item->name)->toBe('ゲストユーザーのアイテム');
        });

        test('存在しない気になるものを更新しようとした場合、404エラーとなること', function ($value) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->put(route('items.update', $value))
                ->assertNotFound();
        })->with([
            'パラメータが存在しない数字である' => [10000],
            'パラメータに文字列が入っている' => ['string'],
        ]);
    });

    describe('削除', function () {
        beforeEach(function () {
            $this->item = Item::factory()->for($this->user)->create();
        });

        test('自分の気になるものを削除できること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->from(route('items.show', $this->item))
                ->delete(route('items.destroy', $this->item))
                ->assertRedirect(route('items.index'))
                ->assertSessionHas('success', '削除が完了しました');

            $this->assertModelMissing($this->item);
        });

        test('他のユーザーの気になるものを削除できないこと', function () {
            /** @var TestCase $this */
            $item = Item::factory()->create();

            $this->actingAs($this->user)
                ->delete(route('items.destroy', $item))
                ->assertNotFound();

            $this->assertModelExists($item);
        });

        test('ゲストユーザーの気になるものを削除できないこと', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest('guest-hashed-session')->create();

            $this->actingAs($this->user)
                ->delete(route('items.destroy', $item))
                ->assertNotFound();

            $this->assertModelExists($item);
        });

        test('存在しない気になるものを削除しようとした場合、404エラーとなること', function ($value) {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->delete(route('items.destroy', $value))
                ->assertNotFound();
        })->with([
            'パラメータが存在しない数字である' => [10000],
            'パラメータに文字列が入っている' => ['string'],
        ]);
    });

    describe('フラッシュメッセージ', function () {
        test('登録後、画面に表示されること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->followingRedirects()
                ->post(route('items.store'), validItemData())
                ->assertOk()
                ->assertViewIs('items.index')
                ->assertSee('登録が完了しました');
        });

        test('編集後、画面に表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->for($this->user)->create();

            $this->actingAs($this->user)
                ->followingRedirects()
                ->put(route('items.update', $item), validItemDataUpdate([
                    'name' => '更新時フラッシュメッセージ',
                ]))
                ->assertOk()
                ->assertViewIs('items.index')
                ->assertSee('更新が完了しました');
        });

        test('削除後、画面に表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->for($this->user)->create();

            $this->actingAs($this->user)
                ->followingRedirects()
                ->delete(route('items.destroy', $item))
                ->assertOk()
                ->assertViewIs('items.index')
                ->assertSee('削除が完了しました');
        });

        test('バリデーションエラー時にセッションにsuccessキーが入っていないこと', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->from(route('items.create'))
                ->post(route('items.store'), validItemData([
                    'name' => '',
                ]))->assertInvalid(['name'])
                ->assertSessionMissing('success');
        });
    });

    describe('ヘッダー', function () {
        test('認証済みユーザー向けの表示になっており、ゲストユーザー向けの表示がされていないこと', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->get(route('items.index'))
                ->assertOk()
                ->assertSee([
                    '実績',
                    '設定',
                    'ログアウト',
                ])->assertDontSee([
                    'データを引き継いでアカウント作成',
                    'ゲストログイン終了',
                ]);
        });
    });
});

describe('未認証(ログインユーザーでもゲストユーザーでもない)', function () {
    test('一覧・登録画面、新規登録にアクセスを試みると、ログイン画面にリダイレクトされること', function (string $method, string $routeName) {
        /** @var TestCase $this */
        $this->{$method}(route($routeName))
            ->assertRedirect(route('login'));
    })->with([
        '一覧表示' => ['get', 'items.index'],
        '登録画面' => ['get', 'items.create'],
        '登録' => ['post', 'items.store'],
    ]);

    test('詳細・編集画面へのアクセスを試みた場合、ログイン画面にリダイレクトされること', function (string $routeName) {
        /** @var TestCase $this */
        $item = Item::factory()->create();

        $this->get(route($routeName, $item))
            ->assertRedirect(route('login'));
    })->with([
        '詳細画面' => ['items.show'],
        '編集画面' => ['items.edit'],
    ]);

    test('存在しない「気になるもの」の詳細へのアクセスを試みると、ログイン画面にリダイレクトされること', function () {
        /** @var TestCase $this */
        $this->get(route('items.show', 99999))
            ->assertRedirect(route('login'));
    });

    test('更新を試みるとログイン画面にリダイレクトされること', function () {
        /** @var TestCase $this */
        $item = Item::factory()->create();

        $before = $item->fresh()->getAttributes();

        $this->put(route('items.update', $item), validItemDataUpdate([
            'name' => '未ログインの更新',
        ]))->assertRedirect(route('login'));

        expect($item->fresh()->getAttributes())->toBe($before);
    });

    test('削除を試みるとログイン画面にリダイレクトされること', function () {
        /** @var TestCase $this */
        $item = Item::factory()->create();

        $this->delete(route('items.destroy', $item))
            ->assertRedirect(route('login'));

        $this->assertModelExists($item);
    });
});
