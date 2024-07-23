<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        $data = $data->get();

        return response()->json([
            'message' => 'success get all items',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'unit' => 'required|string|max:255',  // satuan
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
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
    public function update(Request $request, Item $item)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'unit' => 'required|string|max:255',  // satuan
            'stock' => 'required|integer',
            'min_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        // update item
        $data = $validator->validated();
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
