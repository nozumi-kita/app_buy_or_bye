<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticatedSessionController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store');
});

Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
});

Route::resource('items', ItemController::class)->middleware('auth');
