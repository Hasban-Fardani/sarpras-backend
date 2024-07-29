<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $data = Item::with('category:id,name');

        // search by name
        $data->when($request->search, function ($data) use ($request) {
            $data->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        });

        // filter by category
        $data->when($request->category_id, function ($data) use ($request) {
            $data->where('category_id', $request->category_id);
        });

        $data = $data->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get all items',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $data = $request->validated();
        $hashes = hash('sha256', now() . $data['name']);
        $data['code'] = substr($hashes, 0, 10);
        $item = Item::create($data);

        return response()->json([
            'message' => 'success create item',
            'data' => $item
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return response()->json([
            'message' => 'success get item',
            'data' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, Item $item)
    {
        // update item
        $data = $request->validated();
        $item->update($data);

        return response()->json([
            'message' => 'success update item',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'success delete item'
        ]);
    }
}
