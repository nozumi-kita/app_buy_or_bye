<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GuestSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MedalController;
use App\Http\Middleware\EnsureAuthenticatedOrGuestSession;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/items');

Route::controller(AuthenticatedSessionController::class)->group(function () {
    Route::get('/login', 'create')->middleware('guest')->name('login');
    Route::post('/login', 'store')->middleware('guest');
    Route::post('/logout', 'destroy')
        ->middleware([EnsureAuthenticatedOrGuestSession::class])
        ->name('logout');
});

Route::controller(RegisteredUserController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::post('/register', 'store')->middleware('throttle:register');
});

Route::post('/guest-login', GuestSessionController::class)
    ->middleware('guest')
    ->middleware('throttle:guest-login')
    ->name('guest-login');

Route::resource('items', ItemController::class)
    ->middleware([EnsureAuthenticatedOrGuestSession::class])
    ->whereNumber('item');

Route::get('/medals', MedalController::class)
    ->middleware('auth')
    ->name('medals');
