<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatus;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\Item;
use App\Support\GuestSession;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view,item', only: ['show']),
            new Middleware('can:update,item', only: ['edit', 'update']),
            new Middleware('can:delete,item', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (GuestSession::isActive()) {
            return view('items.index', [
                'items' => Item::where('session_id', GuestSession::hashedSessionId())
                    ->orderBy('updated_at', 'DESC')
                    ->get(),
            ]);
        }

        return view('items.index', [
            'items' => Auth::user()->items()->orderBy('updated_at', 'DESC')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        if (GuestSession::isActive()) {
            $hashedSessionId = GuestSession::hashedSessionId();
            $userId = null;
        }

        if (Auth::check()) {
            $hashedSessionId = null;
            $userId = $request->user()->id;
        }

        $item = new Item;
        $item->user_id = $userId;
        $item->session_id = $hashedSessionId;
        $item->name = $request->name();
        $item->price = $request->price();
        $item->memo = $request->memo();
        $item->image_key = 'default';
        $item->status = ItemStatus::Pending;
        $item->status_changed_at = now();

        $item->save();

        return to_route('items.index')
            ->with([
                'success' => '登録が完了しました',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('items.edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->name = $request->name();
        $item->price = $request->price();
        $item->memo = $request->memo();
        $item->status = $request->status();
        if ($item->isDirty('status')) {
            $item->status_changed_at = now();
        }

        $item->save();

        return to_route('items.index')->with([
            'success' => '更新が完了しました',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return to_route('items.index')->with([
            'success' => '削除が完了しました',
        ]);
    }
}
