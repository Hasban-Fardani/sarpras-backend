<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemInRequest;
use App\Models\ItemIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // get all item-ins
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

        // paginate
        $data = $data->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get all item-ins',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemInRequest $request)
    {
       // get validated data
        $validatedData = $request->validated();

        // directly create a new array with only the needed keys
        $data = [
            'user_id' => auth()->user()->id,
            'supplier_id' => $validatedData['supplier_id'],
            'total_items' => count($validatedData['items']),
        ];

        $itemIn = ItemIn::create($data);
        $itemIn->details()->createMany($validatedData['items']);

        // update stock
        $item_ids = array_column($validatedData['items'], 'item_id');
        $qtys = array_column($validatedData['items'], 'qty');
        // get last item stock
        
        // Create the SQL query dynamically
        $sql = "UPDATE items SET stock = stock + ELT(FIELD(id, " . implode(',', $item_ids) . "), " . implode(',', $qtys) . ") WHERE id IN (" . implode(',', $item_ids) . ");";

        // Execute the SQL query
        DB::statement($sql);

        return response()->json([
            'message' => 'success create item-in and updated stock',
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
        // update stock
        $item_ids = array_column($itemIn->details->toArray(), 'item_id');
        $qtys = array_column($itemIn->details->toArray(), 'qty');
        
        // Create the SQL query dynamically
        $sql = "UPDATE items SET stock = stock - ELT(FIELD(id, " . implode(',', $item_ids) . "), " . implode(',', $qtys) . ") WHERE id IN (" . implode(',', $item_ids) . ");";

        // Execute the SQL query
        DB::statement($sql);

        $itemIn->delete();
        return response()->json([
            'message' => 'success delete item-in',
        ]);
    }
}
