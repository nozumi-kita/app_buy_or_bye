<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class GuestSession
{
    private const IS_GUEST = 'is_guest';

    public static function start(): void
    {
        Session::regenerate();
        Session::put(self::IS_GUEST, true);
    }

    public static function isActive(): bool
    {
        return (! Auth::check() && Session::get(self::IS_GUEST)) === true;
    }

    public static function hashedSessionId(): ?string
    {
        return self::isActive() ? hash('sha256', Session::getId()) : null;
    }

    public static function end(): void
    {
        Session::forget(self::IS_GUEST);
    }
}
