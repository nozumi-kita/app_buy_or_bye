<?php

use App\Enums\ItemStatus;
use App\Models\Item;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

describe('ゲストユーザー', function () {
    describe('自分のデータ', function () {
        beforeEach(function () {
            $this->hashedSessionId = startGuestSession();
        });

        test('「気になるもの」登録画面が表示されること', function () {
            /** @var TestCase $this */
            $this->get(route('items.create'))
                ->assertOk()
                ->assertViewIs('items.create');
        });

        test('「気になるもの」を登録した場合、user_idにnull、hashed_session_idに自身のセッションIDが入ること', function () {
            /** @var TestCase $this */
            $this->post(route('items.store'), validItemData())
                ->assertRedirect(route('items.index'));

            $item = Item::sole();

            expect($item->user_id)->toBeNull()
                ->and($item->hashed_session_id)->toBe($this->hashedSessionId);
        });

        test('「気になるもの」のhashed_session_idにはハッシュ化されたユーザーのsession_idが入っていること', function () {
            /** @var TestCase $this */
            $this->post(route('items.store'), validItemData())
                ->assertRedirect(route('items.index'));

            $item = Item::sole();

            expect($item->hashed_session_id)->toBe(hash('sha256', Session::getId()));
        });

        test('登録した「気になるもの」が表示されること', function () {
            /** @var TestCase $this */
            $this->post(route('items.store'), validItemData([
                'name' => 'ゲストユーザーのアイテム',
            ]));
            $this->get(route('items.index'))
                ->assertOk()
                ->assertSee('ゲストユーザーのアイテム');
        });

        test('自分の気になるものの詳細が表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest($this->hashedSessionId)->create([
                'name' => 'ゲストパソコン',
            ]);

            $this->get(route('items.show', $item))
                ->assertOk()
                ->assertViewIs('items.show')
                ->assertViewHas('item', $item)
                ->assertSee('ゲストパソコン');
        });

        test('編集画面が表示されること', function () {
            /** @var TestCase $this */
            $item = Item::factory()->guest($this->hashedSessionId)->create([
                'name' => 'ゲストパソコン',
                'price' => '15000',
                'memo' => 'ゲストテスト',
            ]);

            $this->get(route('items.edit', $item))
                ->assertOk()
                ->assertViewIs('items.edit')
                ->assertViewHas('item', $item)
                ->assertSee([
                    'ゲストパソコン',
                    '15000',
                    'ゲストテスト',
                ]);
        });

        test('気になるものを更新し、一覧画面にリダイレクトされること', function () {
            /** @var TestCase $this */
            $guestItem = Item::factory()->guest($this->hashedSessionId)->create();

            $this->from(route('items.edit', $guestItem))
                ->put(route('items.update', $guestItem), validItemDataUpdate([
                    'name' => '更新後',
                    'status' => ItemStatus::Purchased->value,
                ]))->assertRedirect(route('items.index'))
                ->assertSessionHas('success', '更新が完了しました');

            $this->assertDatabaseHas(Item::class, [
                'id' => $guestItem->id,
                'name' => '更新後',
                'status' => ItemStatus::Purchased,
            ]);
        });

        test('気になるものを削除できること', function () {
            /** @var TestCase $this */
            $guestItem = Item::factory()->guest($this->hashedSessionId)->create();

            $this->from(route('items.show', $guestItem))
                ->delete(route('items.destroy', $guestItem))
                ->assertRedirect(route('items.index'))
                ->assertSessionHas('success', '削除が完了しました');

            $this->assertModelMissing($guestItem);
        });
    });

    describe('他のユーザーのデータ', function () {
        beforeEach(function () {
            startGuestSession();
        });

        test('他のゲストユーザーの「気になるもの」が一覧に表示されないこと', function () {
            /** @var TestCase $this */
            Item::factory()->guest('other-guest-session-id')->create([
                'name' => '他のゲストユーザーのアイテム',
            ]);

            $this->get(route('items.index'))
                ->assertOk()
                ->assertDontSee('他のゲストユーザーのアイテム');
        });

        test('他のゲストユーザーの詳細・編集画面の閲覧と更新、削除を試みると404エラーとなること', function (string $method, string $routeName) {
            /** @var TestCase $this */
            $item = Item::factory()->guest('other-guest-session-id')->create();

            $this->{$method}(route($routeName, $item))
                ->assertNotFound();
        })->with([
            '詳細画面' => ['get', 'items.show'],
            '編集画面' => ['get', 'items.edit'],
            '更新' => ['put', 'items.update'],
            '削除' => ['delete', 'items.destroy'],
        ]);

        test('ログインユーザーの「気になるもの」が一覧に表示されないこと', function () {
            /** @var TestCase $this */
            Item::factory()->create(['name' => 'ログインユーザーのアイテム']);

            $this->get(route('items.index'))
                ->assertOk()
                ->assertDontSee('ログインユーザーのアイテム');
        });

        test('ログインユーザーの詳細・編集画面の閲覧と更新、削除を試みると404エラーとなること', function (string $method, string $routeName) {
            /** @var TestCase $this */
            $item = Item::factory()->create();

            $this->{$method}(route($routeName, $item))
                ->assertNotFound();
        })->with([
            '詳細画面' => ['get', 'items.show'],
            '編集画面' => ['get', 'items.edit'],
            '更新' => ['put', 'items.update'],
            '削除' => ['delete', 'items.destroy'],
        ]);
    });
});
