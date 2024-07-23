<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = ItemIn::with('user:id,name');

        // search by user name
        $data->when($request->search, function ($data) use ($request) {
            $data->where(function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                });
            });
        });

        // filter by supplier
        $data->when($request->supplier_id, function ($data) use ($request) {
            $data->where('supplier_id', $request->supplier_id);
        });

        $data = $data->get();

        return response()->json([
            'message' => 'success get all item-ins',
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
            'supplier_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.qty' => 'required|integer',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        // get validated data
        $validatedData = $validator->validated();

        // directly create a new array with only the needed keys
        $data = [
            'user_id' => auth()->user()->id,
            'supplier_id' => $validatedData['supplier_id'],
            'total_items' => count($validatedData['items']),
        ];

        $itemIn = ItemIn::create($data);
        $itemIn->details()->createMany($validatedData['items']);

        return response()->json([
            'message' => 'success create item-in',
            'data' => $itemIn
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemIn $itemIn)
    {
        return response()->json([
            'message' => 'success get item-in',
            'data' => $itemIn->load(['user:id,name', 'details'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemIn $itemIn)
    {
        $itemIn->delete();
        return response()->json([
            'message' => 'success delete item-in',
        ]);
    }
}
