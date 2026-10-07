<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\DeleteAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('settings.edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateAccountRequest $request)
    {
        $user = $request->user();
        $user->name = $request->name();
        $user->email = $request->email();

        $user->save();

        return to_route('settings.index')
            ->with('success', 'アカウント情報の更新が完了しました');
    }

    public function destroy(DeleteAccountRequest $request)
    {
        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->with('success', 'アカウントの削除が完了しました');
    }
}
