<?php

namespace App\Http\Controllers;

use App\Models\Medal;
use App\Support\MedalProgress;
use Illuminate\Http\Request;

class MedalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('medals.index', [
            'medals' => Medal::orderBy('display_order', 'asc')->get(),
            'progress' => MedalProgress::for($request->user()),
        ]);
    }
}
