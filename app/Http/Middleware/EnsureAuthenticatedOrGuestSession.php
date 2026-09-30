<?php

namespace App\Http\Middleware;

use App\Support\GuestSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticatedOrGuestSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() && ! GuestSession::isActive()) {
            return to_route('login');
        }

        return $next($request);
    }
}
