<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\DeleteAccountRequest;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
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
