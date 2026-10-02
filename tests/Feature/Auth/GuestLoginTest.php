<?php

use App\Models\User;
use App\Support\GuestSession;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

test('ログイン画面にゲストログインボタンが表示されていること', function () {
    /** @var TestCase $this */
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('btn-guest-login');
});

test('ゲストログイン成功時に気になるもの一覧画面にリダイレクトされるが、認証はされていないこと', function () {
    /** @var TestCase $this */
    $this->from(route('login'))
        ->post(route('guest-login'))
        ->assertRedirect(route('items.index'))
        ->assertSessionHas('is_guest', true);

    $this->assertGuest();
});

test('ゲストログイン時にセッションIDが更新されること', function () {
    /** @var TestCase $this */
    $userSessionId = Session::getId();

    $this->withCookie(config('session.cookie'), $userSessionId);

    $this->post(route('guest-login'))
        ->assertRedirect(route('items.index'));

    expect(Session::getId())->not()->toBe($userSessionId);
});

test('ゲストログイン後、気になるもの一覧画面にアクセスできること', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->get(route('items.index'))
        ->assertOk()
        ->assertViewIs('items.index');
});

test('ゲストログイン時、ヘッダーの表示が切り替わること', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee([
            'データを引き継いでアカウント作成',
            'ゲストログイン終了',
        ])->assertDontSee([
            '実績',
            '設定',
            'ログアウト',
        ]);
});

test('認証済みユーザーがゲストログインを試みた場合、気になるもの一覧画面にリダイレクトされること', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('guest-login'))
        ->assertRedirect(route('items.index'))
        ->assertSessionMissing('is_guest');
});

test('ゲストログインを終了後に気になるもの一覧画面へのアクセスを試みるとログイン画面にリダイレクトされること', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->from(route('items.index'))
        ->post(route('logout'))
        ->assertRedirect(route('login'))
        ->assertSessionMissing('is_guest');

    $this->get(route('items.index'))
        ->assertRedirect(route('login'));
});

test('ゲストログイン中は、ログイン画面に「ゲストログイン」ボタンが表示されないこと', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->get(route('login'))
        ->assertOk()
        ->assertDontSee('btn-guest-login');
});

test('ゲストログイン中、ユーザー登録画面内にログイン画面に移動するリンクが表示されていないこと', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->get(route('register'))
        ->assertOk()
        ->assertViewIs('auth.register')
        ->assertDontSee('アカウントをお持ちの方はこちら')
        ->assertSee('一覧に戻る');
});

test('アカウント作成時にゲストログインの判定フラグのis_guestが消えること', function () {
    /** @var TestCase $this */
    startGuestSession();

    $this->from(route('register'))
        ->post(route('register'), validRegistrationData(['name' => '新規登録']))
        ->assertRedirect(route('items.index'))
        ->assertSessionMissing('is_guest');

    $user = User::sole();

    expect($user->name)->toBe('新規登録');
});

test('ゲストログイン中に再度ゲストログインをしてもセッションIDは変わらないこと', function () {
    /** @var TestCase $this */
    $hashedSessionId = startGuestSession();

    $this->post(route('guest-login'))
        ->assertRedirect(route('items.index'));

    expect(GuestSession::hashedSessionId())->toBe($hashedSessionId);
});

test('1分間に5回を超えてゲストログインできないこと', function () {
    /** @var TestCase $this */
    foreach (range(1, 5) as $_) {
        $this->post(route('guest-login'))
            ->assertRedirect(route('items.index'));
        $this->post(route('logout'));
    }

    $this->post(route('guest-login'))
        ->assertStatus(429);
});

test('制限後1分間経過すると再びゲストログインできること', function () {
    /** @var TestCase $this */
    foreach (range(1, 5) as $_) {
        $this->post(route('guest-login'))
            ->assertRedirect(route('items.index'));
        $this->post(route('logout'));
    }

    $this->post(route('guest-login'))
        ->assertStatus(429);

    $this->travel(60)->seconds();

    $this->post(route('guest-login'))
        ->assertRedirect(route('items.index'));
});

test('制限後59秒ではまだゲストログインできないこと', function () {
    /** @var TestCase $this */
    $this->freezeTime();

    foreach (range(1, 5) as $_) {
        $this->post(route('guest-login'))
            ->assertRedirect(route('items.index'));
        $this->post(route('logout'));
    }

    $this->travel(59)->seconds();

    $this->post(route('guest-login'))
        ->assertStatus(429);
});
