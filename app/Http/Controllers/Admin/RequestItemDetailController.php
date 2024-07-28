<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestItem;
use App\Models\RequestItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestItemDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RequestItem $requestItem)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $requestItemDetails = $requestItem->details()->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'message' => 'success get all request item details',
            ...$requestItemDetails->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, RequestItem $requestItem)
    {
        $this->checkStatus($requestItem);

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['request_item_id'] = $requestItem->id;
        $data['qty_acc'] = $data['qty'];
        $requestItem->details()->create($data);

        return response()->json([
            'message' => 'success add request item detail',
            'data' => $requestItem->details
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestItemDetail $requestItemDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestItem $requestItem)
    {
        $this->checkStatus($requestItem);

        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer',
            'qty' => 'required|integer',
            'qty_acc' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $detail = $requestItem->details()->where('item_id', $data['item_id'])->first();
        $detail->update($data);

        return response()->json([
            'message' => 'success update submission item detail',
            'data' => $requestItem->details
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestItem $requestItem, RequestItemDetail $requestItemDetail)
    {
        $this->checkStatus($requestItem);

        $requestItemDetail->delete();
        return response()->json([
            'message' => 'success delete request item detail'
        ]);
    }

    function checkStatus(RequestItem $requestItem)
    {
        if ($requestItem->status == 'ditolak' || $requestItem->status == 'disetujui') {
            return response()->json([
                'message' => 'request item already ' . $requestItem->status
            ], 400);
        }
    }
}
