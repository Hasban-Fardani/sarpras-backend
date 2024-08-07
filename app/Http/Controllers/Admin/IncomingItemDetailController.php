<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\IncomingItem;
use App\Models\IncomingItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomingItemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, IncomingItem $IncomingItem)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $data = IncomingItemDetail::with(['item:code,name'])
            ->where('incoming_item_code', $IncomingItem->id)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get item in detail',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, IncomingItem $IncomingItem)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'item_code' => 'required|integer',
            'qty' => 'required|integer',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        // check if item exist in item in
        $IncomingItemDetail = $IncomingItem->details()->where('item_code', $request->item_code)->first();
        if ($IncomingItemDetail) {
            $IncomingItemDetail->update([
                'qty' => $IncomingItemDetail->qty + $request->qty
            ]);
            return response()->json([
                'message' => 'success update item in detail',
                'data' => $IncomingItem->details
            ]);
        }

        $IncomingItem->details()->create($validator->validated());

        // add item stock
        Item::where('id', $request->item_code)->increment('stock', $request->qty);

        return response()->json([
            'message' => 'success add item in detail',
            'data' => $IncomingItem->details
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingItem $IncomingItem, string $idDetails)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'item_code' => 'integer',
            'qty' => 'integer',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $IncomingItemDetail = IncomingItemDetail::find($idDetails);
        $IncomingItemDetail->update($request->all());
        return response()->json([
            'message' => 'success update item in detail',
            'data' => $IncomingItemDetail
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingItem $IncomingItem, string $idDetails)
    {
        $IncomingItemDetail = IncomingItemDetail::find($idDetails);
        $IncomingItemDetail->delete();
        return response()->json([
            'message' => 'success delete item in detail',
            'data' => $IncomingItem->details
        ]);
    }
}
