<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemIn;
use App\Models\ItemInDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemInDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ItemIn $itemIn)
    {
        return response()->json([
            'message' => 'success get item in detail',
            'data' => $itemIn->details()
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

        $itemIn->details()->create($request->all());
        return response()->json([
            'message' => 'success add item in detail',
            'data' => $itemIn->details()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemIn $itemIn, ItemInDetail $itemInDetail)
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

        $itemInDetail->update($request->all());
        return response()->json([
            'message' => 'success update item in detail',
            'data' => $itemIn->details()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemIn $itemIn, ItemInDetail $itemInDetail)
    {
        $itemInDetail->delete();
        return response()->json([
            'message' => 'success delete item in detail',
            'data' => $itemIn->details()
        ]);
    }
}