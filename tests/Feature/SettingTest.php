<?php

use App\Models\Item;
use App\Models\Medal;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
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

describe('設定編集画面', function () {
    test('画面が表示されること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->get(route('account.edit'))
            ->assertOk()
            ->assertSee(['Test太郎', 'test@example.com'])
            ->assertViewIs('account.edit');
    });

    test('情報の更新ができること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), [
                'name' => 'Test花子',
                'email' => 'test_hanako@example.com',
                'password' => 'password',
            ])
            ->assertRedirect(route('settings.index'));

        $this->assertDatabaseMissing(User::class, [
            'name' => 'Test太郎',
            'email' => 'test@example.com',
        ])
            ->assertDatabaseHas(User::class, [
                'name' => 'Test花子',
                'email' => 'test_hanako@example.com',
            ]);
    });

    test('各項目が更新できること', function (array $overrides) {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData($overrides))
            ->assertRedirect(route('settings.index'));

        $this->assertDatabaseHas(User::class, $overrides);
    })->with([
        'nameが1文字' => [['name' => 'a']],
        'nameが50文字' => [['name' => str_repeat('a', 50)]],
    ]);

    test('メールアドレスは小文字で保存されること', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'email' => 'Different@ExAmple.Com',
            ]))
            ->assertRedirect(route('settings.index'));

        $user = User::sole();

        expect($user->email)->toBe('different@example.com');
    });

    test('既存のメールアドレスには更新できないこと', function () {
        /** @var TestCase $this */
        User::factory()->create([
            'name' => '他のユーザー',
            'email' => 'other_user@example.com',
        ]);

        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'email' => 'other_user@example.com',
            ]))->assertInvalid('email');

        $userId = $this->user->id;

        $this->assertDatabaseMissing(User::class, [
            'id' => $userId,
            'email' => 'other_user@example.com',
        ]);

        $this->assertDatabaseHas(User::class, [
            'id' => $userId,
            'email' => 'test@example.com',
        ]);
    });

    test('大文字小文字だけが違うメールアドレスを入力しても、既存のメールアドレスには更新できないこと', function () {
        /** @var TestCase $this */
        User::factory()->create(['email' => 'other_user@example.com']);

        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'email' => 'OTHER_USER@EXAMPLE.COM',
            ]))->assertRedirect(route('account.edit'))
            ->assertInvalid('email');

        $this->user->refresh();

        expect($this->user->email)->toBe('test@example.com');
    });

    test('各項目が空・不正な形式なら更新できないこと', function (array $overrides, array $errors) {
        /** @var TestCase $this */
        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => 'Test太郎',
                'email' => 'test_hanako@example.com',
                ...$overrides,
            ]))->assertRedirect(route('account.edit'))
            ->assertInvalid($errors);

        expect(User::sole()->only(['name', 'email']))
            ->toBe([
                'name' => 'Test太郎',
                'email' => 'test@example.com',
            ]);
    })->with([
        'nameが空' => [['name' => ''], ['name']],
        'nameが50文字を超える' => [['name' => str_repeat('a', 51)], ['name']],
        'emailが空' => [['email' => ''], ['email']],
        'emailにドメインがない' => [['email' => 'email'], ['email']],
        'emailがドットレスドメインである' => [['email' => 'test@test'], ['email']],
        'emailに引用符が入っている' => [['email' => '"test"@example.com'], ['email']],
        'emailにカッコが入っている' => [['email' => '(test)@example.com'], ['email']],
        'emailにIPアドレス(IPV4)が直書きされている' => [['email' => 'test@[192.0.2.1]'], ['email']],
        'emailにIPアドレス(IPV6)が直書きされている' => [['email' => 'test@[IPv6:2001:db8::1]'], ['email']],
        'passwordが空' => [['password' => ''], ['password']],
        'passwordが違う' => [['password' => 'wrongpassword'], ['password']],
    ]);

    test('1分間に5回を超えて更新を試行できないこと', function () {
        /** @var TestCase $this */
        $this->actingAs($this->user);

        foreach (range(1, 5) as $_) {
            $this->from(route('account.edit'))
                ->put(route('account.update'), validAccountUpdateData());
        }

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => '名前変更',
            ]))
            ->assertStatus(429);

        expect(User::sole()->only('name'))->toBe(['name' => 'Test太郎']);
    });

    test('アカウント情報更新の制限後、1分経過すると再度更新が行えること', function () {
        /** @var TestCase $this */
        $this->freezeTime();

        $this->actingAs($this->user);

        foreach (range(1, 5) as $_) {
            $this->from(route('account.edit'))
                ->put(route('account.update'), validAccountUpdateData());
        }

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData())
            ->assertStatus(429);

        $this->travel(60)->seconds();

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => '更新再開',
            ]))->assertRedirect(route('settings.index'));

        expect(User::sole()->only('name'))->toBe(['name' => '更新再開']);
    });

    test('アカウント情報更新の制限後、59秒経過時点ではまだ更新が行えないこと', function () {
        /** @var TestCase $this */
        $this->freezeTime();

        $this->actingAs($this->user);

        foreach (range(1, 5) as $_) {
            $this->from(route('account.edit'))
                ->put(route('account.update'), validAccountUpdateData());
        }

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData())
            ->assertStatus(429);

        $this->travel(59)->seconds();

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => '名前変更',
            ]))
            ->assertStatus(429);

        expect(User::sole()->only('name'))->toBe(['name' => 'Test太郎']);
    });

    test('未認証・ゲストユーザーは編集画面に入れず、更新もできないこと', function () {
        /** @var TestCase $this */
        $this->get(route('account.edit'))
            ->assertRedirect(route('login'));

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => 'Test花子',
                'email' => 'test_hanako@example.com',
            ]))->assertRedirect(route('login'));

        expect(User::sole()->only(['name', 'email']))->toBe([
            'name' => 'Test太郎',
            'email' => 'test@example.com',
        ]);

        startGuestSession();

        $this->get(route('account.edit'))
            ->assertRedirect(route('login'));

        $this->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => 'Test花子',
                'email' => 'test_hanako@example.com',
            ]))->assertRedirect(route('login'));

        expect(User::sole()->only(['name', 'email']))->toBe([
            'name' => 'Test太郎',
            'email' => 'test@example.com',
        ]);
    });

    test('他のユーザーの情報は変更されないこと', function () {
        /** @var TestCase $this */
        $otherUser = User::factory()->create([
            'name' => '他のユーザー',
            'email' => 'other_user@example.com',
        ]);

        $this->actingAs($this->user)
            ->from(route('account.edit'))
            ->put(route('account.update'), validAccountUpdateData([
                'name' => '私の名前',
                'email' => 'my_email@example.com',
            ]))->assertRedirect(route('settings.index'));

        $otherUser->refresh();

        expect($otherUser->only(['name', 'email']))->toBe([
            'name' => '他のユーザー',
            'email' => 'other_user@example.com',
        ]);

        $this->user->refresh();

        expect($this->user->only(['name', 'email']))->toBe([
            'name' => '私の名前',
            'email' => 'my_email@example.com',
        ]);
    });

    describe('DNSチェック', function () {
        test('本番環境では、メールを受け取れないドメインでは更新できないこと', function () {
            /** @var TestCase $this */
            $this->app->detectEnvironment(fn () => 'production');

            // 環境を production にすると、テスト中は無効なCSRFチェックが有効になるため外す
            $this->withoutMiddleware(ValidateCsrfToken::class);

            $this->actingAs($this->user)
                ->from(route('account.edit'))
                ->put(route('account.update'), validAccountUpdateData([
                    'email' => 'test@example.test',
                ]))->assertRedirect(route('account.edit'))
                ->assertInvalid('email');

            $this->assertDatabaseCount(User::class, 1);
            $this->assertDatabaseHas(User::class, [
                'email' => 'test@example.com',
            ]);
        });

        test('本番環境以外では、メールを受け取れないドメインでも更新できること', function () {
            /** @var TestCase $this */
            $this->actingAs($this->user)
                ->from(route('account.edit'))
                ->put(route('account.update'), validAccountUpdateData([
                    'email' => 'test@example.test',
                ]))->assertRedirect(route('settings.index'));

            $this->assertDatabaseHas(User::class, [
                'email' => 'test@example.test',
            ]);
        });
    });
});
