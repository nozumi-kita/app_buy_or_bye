<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatus;
use App\Http\Requests\Item\StoreItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $item = new Item;
        $item->user_id = $request->user()->id;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
