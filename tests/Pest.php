<?php

use App\Enums\ItemStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
