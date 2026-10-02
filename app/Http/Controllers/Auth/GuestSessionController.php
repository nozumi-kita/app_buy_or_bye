<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\GuestSession;
use Illuminate\Http\Request;

class GuestSessionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (! GuestSession::isActive()) {
            GuestSession::start();
        }

        return to_route('items.index');
    }
}
