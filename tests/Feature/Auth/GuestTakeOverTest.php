<?php

use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

beforeEach(function () {
    $this->hashedSessionId = startGuestSession();
});

test('アカウント作成した場合、ゲストユーザー時のデータが引き継がれること', function () {
    /** @var TestCase $this */
    $this->post(route('items.store'), validItemData());

    $item = Item::sole();

    expect($item->hashed_session_id)->toBe($this->hashedSessionId)
        ->and($item->user_id)->toBeNull();

    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'));

    $user = User::sole();
    $item->refresh();

    expect($item->hashed_session_id)->toBeNull()
        ->and($item->user_id)->toBe($user->id);
});

test('引き継いだ「気になるもの」が登録後の一覧に表示されること', function () {
    /** @var TestCase $this */
    $this->post(route('items.store'), validItemData(['name' => '引き継ぎアイテム']));
    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'));

    $this->assertAuthenticated();

    $this->withCookie(config('session.cookie'), Session::id());

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('引き継ぎアイテム');
});

test('引き継ぎ時に、itemのupdated_atが更新されないこと', function () {
    /** @var TestCase $this */
    $this->post(route('items.store'), validItemData());

    $item = Item::sole();
    $itemRegistrationTime = $item->updated_at;

    $this->travel(1)->hours();
    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'));

    $item->refresh();

    $user = User::sole();

    expect($item->user_id)->toBe($user->id)->and($item->hashed_session_id)->toBeNull();
    expect(($item->updated_at)->isSameSecond($itemRegistrationTime))->toBeTrue();
});

test('ゲストログイン状態で、既存アカウントにログインしてもデータが引き継がれないこと', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $this->post(route('items.store'), validItemData());

    $item = Item::sole();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'))
        ->assertSessionMissing('is_guest');

    $this->assertAuthenticatedAs($user);

    $item->refresh();

    expect($item->user_id)->toBeNull()->and($item->hashed_session_id)->toBe($this->hashedSessionId);
});

test('ゲストログイン終了後にアカウントを作成しても引き継ぎは行われないこと', function () {
    /** @var TestCase $this */
    $item = Item::factory()->guest($this->hashedSessionId)->create();

    $this->post(route('logout'));
    $this->withCookie(config('session.cookie'), Session::id());

    $this->post(route('register'), validRegistrationData([
        'name' => 'ゲストログイン終了後作成ユーザー',
    ]))->assertRedirect(route('items.index'));

    $user = User::sole();
    expect($user->name)->toBe('ゲストログイン終了後作成ユーザー');

    $item->refresh();
    expect($item->user_id)->toBeNull()
        ->and($item->hashed_session_id)->toBe($this->hashedSessionId);
});

test('アカウント作成時に自分のゲストデータのみ引き継ぎ、他者のゲストデータを引き継がないこと', function () {
    /** @var TestCase $this */
    $otherItem = Item::factory()->guest('other-guest-session-id')->create();

    $this->post(route('items.store'), validItemData(['name' => '自分の引き継ぎアイテム']));
    $myItem = Item::where('name', '自分の引き継ぎアイテム')->sole();

    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'));

    $myItem->refresh();
    $otherItem->refresh();
    $user = User::sole();

    expect($myItem->user_id)->toBe($user->id)
        ->and($otherItem->user_id)->toBeNull()
        ->and($otherItem->hashed_session_id)->toBe('other-guest-session-id');
});

test('ゲストが「気になるもの」を1つも登録していない状態から引き継ぎをしてもユーザー登録できること', function () {
    /** @var TestCase $this */
    $this->assertDatabaseCount(Item::class, 0);

    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'))
        ->assertSessionMissing('is_guest');

    $this->assertAuthenticated();
    $this->assertDatabaseCount(Item::class, 0);
});
