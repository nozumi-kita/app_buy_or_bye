<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

test('ログイン画面が表示されること', function () {
    /** @var TestCase $this */
    $this->get(route('login'))->assertOk()->assertViewIs('auth.login');
});

test('ユーザーがログインでき、認証状態になること', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->assertAuthenticatedAs($user);
});

test('大文字を含むメールアドレスを入力してもログインできること', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->post(route('login'), [
        'email' => 'TesT@Example.Com',
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->assertAuthenticatedAs($user);
});

test('パスワードが間違っている場合、ログインできないこと', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ])->assertInvalid(['email']);

    $this->assertGuest();
});

test('存在しないメールアドレスではログインできないこと', function () {
    /** @var TestCase $this */
    $this->post(route('login'), [
        'email' => 'wrong@example.com',
        'password' => 'password',
    ])->assertInvalid(['email']);

    $this->assertGuest();
});

test('各項目が空なら、ログインできないこと', function () {
    /** @var TestCase $this */
    $this->post(route('login'), [
        'email' => '',
        'password' => '',
    ])->assertInvalid(['email', 'password']);

    $this->assertGuest();
});

test('ログイン済みユーザーはログイン画面に入れないこと', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect(route('items.index'));
});

test('ログインが60秒間に5回連続で失敗すると、一時的にログインできなくなること', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    foreach (range(1, 5) as $_) {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ])->assertInvalid('email');
    }

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertInvalid(['email' => '秒後にお試しください。']);

    $this->assertGuest();
});

test('メールアドレスの文字を大文字・小文字で変えた場合も失敗の回数として合算されること', function () {
    /** @var TestCase $this */
    $userEmail = 'test@example.com';

    User::factory()->create([
        'email' => $userEmail,
    ]);

    $emailArray = [
        'test@example.com',
        'TEST@EXAMPLE.COM',
        'tesT@examplE.coM',
        'Test@Example.Com',
        'TesT@ExamplE.CoM',
    ];

    foreach ($emailArray as $email) {
        $this->post(route('login'), [
            'email' => $email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->post(route('login'), [
        'email' => $userEmail,
        'password' => 'password',
    ])->assertInvalid(['email' => '秒後にお試しください。']);

    $this->assertGuest();
});

test('レート制限が他のユーザーのログインに影響しないこと', function () {
    /** @var TestCase $this */
    $lockedUser = User::factory()->create();
    $otherUser = User::factory()->create();

    foreach (range(1, 5) as $_) {
        $this->post(route('login'), [
            'email' => $lockedUser->email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->post(route('login'), [
        'email' => $otherUser->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->assertAuthenticatedAs($otherUser);
});

test('ログインに成功すると失敗回数がリセットされること', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();

    foreach (range(1, 4) as $_) {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->isAuthenticated();

    Auth::logout();

    foreach (range(1, 4) as $_) {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->assertAuthenticatedAs($user);
});

test('ログインに5回連続で失敗しても、60秒経過すると再度ログインが可能になること', function () {
    /** @var TestCase $this */
    $this->freezeTime();
    $user = User::factory()->create();

    foreach (range(1, 5) as $_) {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->travel(60)->seconds();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('items.index'));

    $this->assertAuthenticatedAs($user);
});

test('ログインに5回連続で失敗後、59秒の経過では再度ログインできないこと', function () {
    /** @var TestCase $this */
    $this->freezeTime();
    $user = User::factory()->create();

    foreach (range(1, 5) as $_) {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);
    }

    $this->travel(59)->seconds();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertInvalid(['email' => '秒後にお試しください。']);

    $this->assertGuest();
});
