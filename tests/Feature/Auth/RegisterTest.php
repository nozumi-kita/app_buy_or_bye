<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

test('登録画面が表示されること', function () {
    $this->get(route('register'))->assertOk()->assertViewIs('auth.register');
});

test('ユーザー登録が成功し、DBに保存され、認証状態になること', function () {
    $this->post(route('register'), validRegistrationData())
        ->assertRedirect(route('items.index'));

    $this->assertDatabaseHas(User::class, ['email' => 'test@example.com']);
    $this->assertAuthenticated();
});

test('各項目が登録できること', function (array $overrides) {
    $this->post(route('register'), validRegistrationData($overrides))
        ->assertRedirect(route('items.index'));

    $this->assertDatabaseCount('users', 1);
    $this->assertAuthenticated();
})->with([
    'nameが1文字' => [['name' => 'a']],
    'nameが50文字' => [['name' => str_repeat('a', 50)]],
]);

test('メールアドレスは小文字で保存されること', function () {
    $this->post(route('register'), validRegistrationData([
        'email' => 'TEst@ExAmple.Com',
    ]));

    $user = User::sole();

    expect($user->email)->toBe('test@example.com');
});

test('既存のメールアドレスでは登録できないこと', function () {
    User::factory()->create(['email' => 'test@example.com']);
    $this->post(route('register'), validRegistrationData())
        ->assertInvalid(['email']);

    $this->assertDatabaseCount('users', 1);
    $this->assertGuest();
});

test('大文字小文字だけが違うメールアドレスでは登録できないこと', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $this->post(route('register'), validRegistrationData([
        'email' => 'TesT@Example.coM',
    ]))->assertInvalid('email');
});

test('各項目が空・不正な形式なら登録できないこと', function (array $overrides, array $errors) {
    $this->post(route('register'), validRegistrationData($overrides))
        ->assertInvalid($errors);

    $this->assertDatabaseCount('users', 0);
    $this->assertGuest();
})->with([
    'nameが空' => [['name' => ''], ['name']],
    'nameが50文字を超える' => [['name' => str_repeat('a', 51)], ['name']],
    'emailが空' => [['email' => ''], ['email']],
    'emailの形式が不正' => [['email' => 'email'], ['email']],
    'passwordが空' => [['password' => '', 'password_confirmation' => ''], ['password']],
    'passwordが8文字未満' => [['password' => 'pass', 'password_confirmation' => 'pass'], ['password']],
    'password確認が不一致' => [['password_confirmation' => 'different'], ['password']],
]);

test('パスワードがハッシュ化されていること', function () {
    $userData = validRegistrationData();

    $this->post(route('register'), $userData);

    $user = User::firstWhere('email', $userData['email']);

    expect($user->password)->not->toBe($userData['password']);
    expect(Hash::check($userData['password'], $user->password))->toBeTrue();
});

test('認証済みのユーザーは登録画面に入れないこと', function () {
    $this->actingAs(User::factory()->create())->get(route('register'))->assertRedirect(route('items.index'));
});

test('1分間に10回を超えて登録できないこと', function () {
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
