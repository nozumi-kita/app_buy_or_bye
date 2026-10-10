<?php

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

describe('未認証(ログインユーザーでもゲストユーザーでもない)', function () {
    test('登録画面が表示されること', function () {
        /** @var TestCase $this */
        $this->get(route('register'))->assertOk()->assertViewIs('auth.register');
    });

    test('登録画面にログイン画面へのリンクが表示され、「一覧に戻る」は表示されないこと', function () {
        /** @var TestCase $this */
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('アカウントをお持ちの方はこちら')
            ->assertDontSee('一覧に戻る');
    });

    test('ユーザー登録が成功し、DBに保存され、認証状態になること', function () {
        /** @var TestCase $this */
        $this->post(route('register'), validRegistrationData())
            ->assertRedirect(route('items.index'));

        $this->assertDatabaseHas(User::class, ['email' => 'test@example.com']);
        $this->assertAuthenticated();
    });

    test('各項目が登録できること', function (array $overrides) {
        /** @var TestCase $this */
        $this->post(route('register'), validRegistrationData($overrides))
            ->assertRedirect(route('items.index'));

        $this->assertDatabaseCount('users', 1);
        $this->assertAuthenticated();
    })->with([
        'nameが1文字' => [['name' => 'a']],
        'nameが50文字' => [['name' => str_repeat('a', 50)]],
    ]);

    test('メールアドレスは小文字で保存されること', function () {
        /** @var TestCase $this */
        $this->post(route('register'), validRegistrationData([
            'email' => 'TEst@ExAmple.Com',
        ]));

        $user = User::sole();

        expect($user->email)->toBe('test@example.com');
    });

    test('既存のメールアドレスでは登録できないこと', function () {
        /** @var TestCase $this */
        User::factory()->create(['email' => 'test@example.com']);
        $this->post(route('register'), validRegistrationData())
            ->assertInvalid(['email']);

        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    });

    test('大文字小文字だけが違うメールアドレスでは登録できないこと', function () {
        /** @var TestCase $this */
        User::factory()->create(['email' => 'test@example.com']);

        $this->post(route('register'), validRegistrationData([
            'email' => 'TesT@Example.coM',
        ]))->assertInvalid('email');
    });

    test('各項目が空・不正な形式なら登録できないこと', function (array $overrides, array $errors) {
        /** @var TestCase $this */
        $this->post(route('register'), validRegistrationData($overrides))
            ->assertInvalid($errors);

        $this->assertDatabaseCount('users', 0);
        $this->assertGuest();
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
        'passwordが空' => [['password' => '', 'password_confirmation' => ''], ['password']],
        'passwordが8文字未満' => [['password' => 'pass', 'password_confirmation' => 'pass'], ['password']],
        'password確認が不一致' => [['password_confirmation' => 'different'], ['password']],
    ]);

    test('パスワードがハッシュ化されていること', function () {
        /** @var TestCase $this */
        $userData = validRegistrationData();

        $this->post(route('register'), $userData);

        $user = User::firstWhere('email', $userData['email']);

        expect($user->password)->not->toBe($userData['password']);
        expect(Hash::check($userData['password'], $user->password))->toBeTrue();
    });

    test('1分間に10回を超えて登録できないこと', function () {
        /** @var TestCase $this */
        foreach (range(1, 10) as $i) {
            $this->post(route('register'), validRegistrationData([
                'email' => "test{$i}@example.com",
            ]));
            Auth::logout();
        }

        $this->post(route('register'), validRegistrationData())
            ->assertStatus(429);

        $this->assertDatabaseCount('users', 10);
    });
});

describe('認証済みユーザー', function () {
    test('登録画面に入れないこと', function () {
        /** @var TestCase $this */
        $this->actingAs(User::factory()->create())->get(route('register'))->assertRedirect(route('items.index'));
    });
});

describe('DNSチェック', function () {
    test('本番環境では、メールを受け取れないドメインでは登録できないこと', function () {
        /** @var TestCase $this */
        $this->app->detectEnvironment(fn () => 'production');

        // 環境を production にすると、テスト中は無効なCSRFチェックが有効になるため外す
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->from(route('register'))
            ->post(route('register'), validRegistrationData([
                'email' => 'test@example.test',
            ]))->assertRedirect(route('register'))
            ->assertInvalid('email');

        $this->assertDatabaseCount(User::class, 0);
    });

    test('本番環境以外では、メールを受け取れないドメインでも登録できること', function () {
        /** @var TestCase $this */
        $this->from(route('register'))
            ->post(route('register'), validRegistrationData([
                'email' => 'test@example.test',
            ]))->assertRedirect(route('items.index'));

        $this->assertDatabaseHas(User::class, [
            'email' => 'test@example.test',
        ]);
    });
});

describe('パスワードのバリデーションの強化', function () {
    beforeEach(function () {
        /** @var TestCase $this */
        $this->app->detectEnvironment(fn () => 'production');

        // 環境をproductionにすると、テスト中は無効なCSRFチェックが有効になるため外す
        $this->withoutMiddleware(ValidateCsrfToken::class);
    });

    test('本番環境では、大文字・小文字・記号・数字いずれかが足りない場合バリデーションで弾かれること', function (string $password) {
        /** @var TestCase $this */
        $this->from(route('register'))
            ->post(route('register'), validRegistrationData([
                'email' => 'test@example.test',
                'password' => $password,
                'password_confirmation' => $password,
            ]))->assertRedirect(route('register'))
            ->assertInvalid('password');
    })->with([
        '小文字なし' => ['PASSWORD1@'],
        '大文字なし' => ['password1@'],
        '記号なし' => ['PASSword1'],
        '数字なし' => ['PASSword@'],
    ]);

    test('本番環境では、条件を満たす場合パスワードは通ること', function () {
        /** @var TestCase $this */
        $this->from(route('register'))
            ->post(route('register'), validRegistrationData([
                'email' => 'test@example.test',
                'password' => 'Tdsfdd@1',
                'password_confirmation' => 'Tdsfdd@1',
            ]))->assertRedirect(route('register'))
            ->assertValid('password');
    });

    // ネット環境のない場所では、漏洩の確認がされずバリデーションで弾かれないのでテストが失敗します。
    test('本番環境では、情報漏洩した可能性のあるパスワードではバリデーションで弾かれること', function () {
        /** @var TestCase $this */
        $this->from(route('register'))
            ->post(route('register'), validRegistrationData([
                'email' => 'test@example.test',
                'password' => 'PASSword@1',
                'password_confirmation' => 'PASSword@1',
            ]))->assertRedirect(route('register'))
            ->assertInvalid('password');
    });
});
