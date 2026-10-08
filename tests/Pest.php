<?php

use App\Enums\ItemStatus;
use App\Support\GuestSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

function validRegistrationData(array $overrides = []): array
{
    return array_merge([
        'name' => 'test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ], $overrides);
}

function validItemData(array $overrides = []): array
{
    return array_merge([
        'name' => 'テスト',
        'price' => '10000',
        'memo' => 'テストメモ',
    ], $overrides);
}

function validItemDataUpdate(array $overrides = []): array
{
    return validItemData(array_merge([
        'status' => ItemStatus::Pending->value,
    ], $overrides));
}

function startGuestSession(): string
{
    test()->from(route('login'))
        ->post(route('guest-login'))
        ->assertRedirect(route('items.index'));

    test()->withCookie(config('session.cookie'), Session::getId());

    return GuestSession::hashedSessionId();
}

function validAccountUpdateData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Test太郎',
        'email' => 'test@example.com',
        'password' => 'password',
    ], $overrides);
}
