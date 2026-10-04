<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Support\GuestSession;
use App\Support\ItemOwner;
use App\Support\MedalAwarder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register')
            ->with('guestLoggedIn', GuestSession::isActive());
    }

    public function store(RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name(),
                'email' => $request->email(),
                'password' => $request->password(),
            ]);

            if (GuestSession::isActive()) {
                ItemOwner::current()->transferItemsTo($user);
            }

            return $user;
        });

        $awardedMedalNames = MedalAwarder::awardFor($user)->pluck('name')->all();

        Auth::login($user);
        GuestSession::end();

        return redirect()->intended(route('items.index'))
            ->with('awardedMedalNames', $awardedMedalNames);
    }
}
