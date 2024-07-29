<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = RequestItem::with('user:id,name');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $data->when($request->search, function ($data) use ($request) {
            $data->where(function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            });
        });

        $data->when($request->status, function ($data) use ($request) {
            $data->where('status', $request->status);
        });

        $data->when($request->start_date && $request->end_date, function ($data) use ($request) {
            $data->whereBetween('created_at', [$request->date, $request->end_date]);
        });

        $data = $data->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get all request items',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validated();
        // directly create a new array with only the needed keys
        $data = [
            'user_id' => auth()->user()->id,
            'total_items' => count($validatedData['items']),
        ];

        // create request item
        $request_item = RequestItem::create($data);

        // get items
        $items = $validatedData['items'];
        // set default qty_acc to same as qty
        foreach ($items as $key => $item) {
            $items[$key]['qty_acc'] = $item['qty'];
        }

        // create request item details
        $request_item->details()->createMany($items);

        // return response
        return response()->json([
            'message' => 'success create request item',
            'data' => $request_item
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestItem $request)
    {
        return response()->json([
            'message' => 'success get submission item',
            'data' => $request->load(['user:id,name', 'details'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $requestHttp, RequestItem $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestItem $request)
    {
        $request->delete();
        return response()->json([
            'message' => 'success delete request item'
        ]);
    }
}
