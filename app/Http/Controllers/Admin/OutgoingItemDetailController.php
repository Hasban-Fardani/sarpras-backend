<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\OutgoingItem;
use App\Models\ItemOutDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutgoingItemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, OutgoingItem $itemOut)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $itemOutDetails = $itemOut->details()->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'message' => 'success get item out detail',
            ...$itemOutDetails->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OutgoingItem $itemOut)
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
        $itemOutDetail = $itemOut->details()->where('item_code', $request->item_code)->first();
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
        Item::where('id', $request->item_code)->decrement('stock', $request->qty);

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
    public function destroy(OutgoingItem $itemOut, string $idDetails)
    {
        $itemOutDetail = ItemOutDetail::find($idDetails);
        $itemOutDetail->delete();
        return response()->json([
            'message' => 'success delete item in detail',
            'data' => $itemOut->details
        ]);
    }
}
