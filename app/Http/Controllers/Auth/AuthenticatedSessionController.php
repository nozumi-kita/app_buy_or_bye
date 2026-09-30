<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\GuestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login')
            ->with('guestLoggedIn', GuestSession::isActive());
    }

    public function store(LoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        if (! Auth::attempt($request->credentials())) {
            $request->recordFailedAttempt();

            throw ValidationException::withMessages([
                'email' => 'メールアドレスまたはパスワードが正しくありません。',
            ]);
        }

        $request->clearFailedAttempts();
        $request->session()->regenerate();
        GuestSession::end();

        return redirect()->intended(route('items.index'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
