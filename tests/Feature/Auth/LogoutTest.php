<?php

use App\Models\User;
use Tests\TestCase;

test('ログアウトをし、認証状態を解除すること', function () {
    /** @var TestCase $this */
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))->assertRedirect(route('login'));

    $this->assertGuest();

    $this->get(route('items.index'))->assertRedirect(route('login'));
});

test('未認証状態でログアウトを試みるとログイン画面にリダイレクトされること', function () {
    /** @var TestCase $this */
    $this->post(route('logout'))->assertRedirect(route('login'));
});
