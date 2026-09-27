<?php

use App\Models\User;

test('ログアウトをし、認証状態を解除すること', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))->assertRedirect(route('login'));

    $this->assertGuest();

    $this->get(route('items.index'))->assertRedirect(route('login'));
});

test('未認証状態でログアウトを試みるとログイン画面にリダイレクトされること', function () {
    $this->post(route('logout'))->assertRedirect(route('login'));
});
