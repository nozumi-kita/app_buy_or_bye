<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatus;
use App\Http\Requests\Item\StoreItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Models\Item;
use App\Support\ItemOwner;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

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
        $items = ItemOwner::current()->items();

        return view('items.index', [
            'items' => $items->orderBy('updated_at', 'DESC')->get(),
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
        $itemOwner = ItemOwner::current();

        $item = new Item;
        $item->user_id = $itemOwner->userId;
        $item->hashed_session_id = $itemOwner->hashedSessionId;
        $item->name = $request->name();
        $item->price = $request->price();
        $item->memo = $request->memo();
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
