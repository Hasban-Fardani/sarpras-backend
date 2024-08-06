<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemIn;
use App\Models\ItemInDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemInDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ItemIn $itemIn)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $data = ItemInDetail::with(['item:id,name'])
            ->where('item_in_id', $itemIn->id)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get item in detail',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ItemIn $itemIn)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer',
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
        $itemInDetail = $itemIn->details()->where('item_id', $request->item_id)->first();
        if ($itemInDetail) {
            $itemInDetail->update([
                'qty' => $itemInDetail->qty + $request->qty
            ]);
            return response()->json([
                'message' => 'success update item in detail',
                'data' => $itemIn->details
            ]);
        }

        $itemIn->details()->create($validator->validated());

        // add item stock
        Item::where('id', $request->item_id)->increment('stock', $request->qty);

        return response()->json([
            'message' => 'success add item in detail',
            'data' => $itemIn->details
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemIn $itemIn, string $idDetails)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'item_id' => 'integer',
            'qty' => 'integer',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $itemInDetail = ItemInDetail::find($idDetails);
        $itemInDetail->update($request->all());
        return response()->json([
            'message' => 'success update item in detail',
            'data' => $itemInDetail
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemIn $itemIn, string $idDetails)
    {
        $itemInDetail = ItemInDetail::find($idDetails);
        $itemInDetail->delete();
        return response()->json([
            'message' => 'success delete item in detail',
            'data' => $itemIn->details
        ]);
    }
}
