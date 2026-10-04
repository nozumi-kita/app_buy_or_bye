<?php

use App\Models\Item;
use App\Models\Medal;
use App\Models\User;
use Tests\TestCase;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Test太郎',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
});

describe('設定画面', function () {
    test('設定画面が表示され、ログインユーザー本人のユーザー名とメールアドレスが表示されていること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertViewIs('settings.index')
            ->assertSee(['Test太郎', 'test@example.com']);
    });

    test('他のユーザーの情報が表示されないこと', function () {
        /** @var TestCase $this */
        User::factory()->create([
            'name' => '他者ユーザー',
            'email' => 'other@example.com',
        ]);

        $this->actingAs($this->user)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertDontSee(['他者ユーザー', 'other@example.com']);
    });

    test('未認証・ゲストがアクセスを試みると、ログイン画面にリダイレクトされること', function () {
        /** @var TestCase $this */
        $this->get(route('settings.index'))
            ->assertRedirect(route('login'));

        startGuestSession();

        $this->get(route('settings.index'))
            ->assertRedirect(route('login'));
    });
});

describe('アカウント削除', function () {
    test('正しいパスワードで削除でき、ログアウト状態になること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->followingRedirects()
            ->delete(route('account.destroy'), ['password' => 'password'])
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertSee('アカウントの削除が完了しました');

        $this->assertGuest();

        $this->assertModelMissing($this->user);

    });

    test('パスワードが誤っている・空欄・なしではアカウントが削除されないこと', function (array $payload) {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->from(route('settings.index'))
            ->delete(route('account.destroy'), $payload)
            ->assertRedirect(route('settings.index'))
            ->assertInvalid('password');

        $this->assertModelExists($this->user);
        $this->assertAuthenticated();
    })->with([
        '誤ったパスワード' => [['password' => 'wrongpassword']],
        '空欄' => [['password' => '']],
        'パスワードなし' => [[]],
    ]);

    test('削除したユーザーの気になるもの・獲得したメダルも削除されること', function () {
        /** @var TestCase $this */
        $item = Item::factory()->for($this->user)->create();

        $medal = Medal::factory()->create();
        $this->user->medals()->attach($medal->id, ['acquired_at' => now()]);

        $this->actingAs($this->user)
            ->delete(route('account.destroy'), ['password' => 'password']);

        $this->assertModelMissing($item);
        $this->assertDatabaseMissing('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $this->user->id,
        ]);
    });

    test('他のユーザーのデータは削除されないこと', function () {
        /** @var TestCase $this */
        $otherUser = User::factory()->create();
        $item = Item::factory()->for($otherUser)->create();
        $guestItem = Item::factory()->guest('guest-user-session')->create();

        $medal = Medal::factory()->create();
        $otherUser->medals()->attach($medal->id, ['acquired_at' => now()]);

        $this->actingAs($this->user)
            ->delete(route('account.destroy'), ['password' => 'password']);

        $this->assertModelExists($otherUser);
        $this->assertModelExists($item);
        $this->assertModelExists($guestItem);
        $this->assertDatabaseHas('medal_user', [
            'medal_id' => $medal->id,
            'user_id' => $otherUser->id,
        ]);
    });

    test('アカウント削除を試行できる回数は1分間に5回までであること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user);
        foreach (range(1, 5) as $_) {
            $this->from(route('settings.index'))
                ->delete(route('account.destroy'), [
                    'password' => 'wrongpassword',
                ])
                ->assertInvalid(['password']);
        }

        $this->from(route('settings.index'))
            ->delete(route('account.destroy'), [
                'password' => 'password',
            ])->assertStatus(429);

        $this->assertModelExists($this->user);
    });

    test('アカウント削除の制限後60秒経過で、再度削除を行えること', function () {
        /** @var TestCase $this */
        $this->freezeTime();

        $this->actingAs($this->user);

        foreach (range(1, 5) as $_) {
            $this->from(route('settings.index'))
                ->delete(route('account.destroy'), [
                    'password' => 'wrongpassword',
                ])
                ->assertInvalid(['password']);
        }

        $this->from(route('settings.index'))
            ->delete(route('account.destroy'), [
                'password' => 'password',
            ])
            ->assertStatus(429);

        $this->travel(60)->seconds();

        $this->followingRedirects()
            ->delete(route('account.destroy'), ['password' => 'password'])
            ->assertOk()
            ->assertViewIs('auth.login');

        $this->assertGuest();

        $this->assertModelMissing($this->user);
    });

    test('アカウント削除の制限後59秒経過では、まだ削除が行えないこと', function () {
        /** @var TestCase $this */
        $this->freezeTime();

        $this->actingAs($this->user);

        foreach (range(1, 5) as $_) {
            $this->from(route('settings.index'))
                ->delete(route('account.destroy'), [
                    'password' => 'wrongpassword',
                ])
                ->assertInvalid(['password']);
        }

        $this->travel(59)->seconds();

        $this->from(route('settings.index'))
            ->delete(route('account.destroy'), [
                'password' => 'password',
            ])
            ->assertStatus(429);

        $this->assertModelExists($this->user);
    });
});
