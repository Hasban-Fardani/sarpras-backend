<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemOut;
use App\Models\ItemOutDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemOutDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ItemOut $itemOut)
    {
        return response()->json([
            'message' => 'success get item in detail',
            'data' => $itemOut->details
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ItemOut $itemOut)
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
        $itemOutDetail = $itemOut->details()->where('item_id', $request->item_id)->first();
        if ($itemOutDetail) {
            $itemOutDetail->update([
                'qty' => $itemOutDetail->qty + $request->qty
            ]);
            return response()->json([
                'message' => 'success update item in detail',
                'data' => $itemOut->details
            ]);
        }

        $itemOut->details()->create($validator->validated());

        // decrement item stock
        Item::where('id', $request->item_id)->decrement('stock', $request->qty);

        return response()->json([
            'message' => 'success add item in detail',
            'data' => $itemOut->details
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idDetails)
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

        $itemOutDetail = ItemOutDetail::find($idDetails);
        $itemOutDetail->update($request->all());
        return response()->json([
            'message' => 'success update item in detail',
            'data' => $itemOutDetail
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemOut $itemOut, string $idDetails)
    {
        $itemOutDetail = ItemOutDetail::find($idDetails);
        $itemOutDetail->delete();
        return response()->json([
            'message' => 'success delete item in detail',
            'data' => $itemOut->details
        ]);
    }
}
