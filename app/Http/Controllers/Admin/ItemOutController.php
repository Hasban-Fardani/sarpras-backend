<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ItemOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // get all item-ins
        $data = ItemOut::with(['operator:id,name', 'division:id,name']);

        // search by operator or division name
        $data->when($request->search, function ($data) use ($request) {
            $searchTerm = '%' . $request->search . '%';
        
            $data->where(function ($query) use ($searchTerm) {
                $query->whereHas('operator', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('division', function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm);
                });
            });
        });        

        // filter by division
        $data->when($request->division_id, function ($data) use ($request) {
            $data->where('division_id', $request->division_id);
        });

        // paginate
        $data = $data->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get all item-outs',
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
            'division_id' => 'required|integer',
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
            'operator_id' => auth()->user()->id,
            'division_id' => $validatedData['division_id'],
            'total_items' => count($validatedData['items']),
        ];

        $itemOut = ItemOut::create($data);
        $itemOut->details()->createMany($validatedData['items']);

        // update stock
        $item_ids = array_column($validatedData['items'], 'item_id');
        $qtys = array_column($validatedData['items'], 'qty');
        // get last item stock
        
        // Create the SQL query dynamically
        $sql = "UPDATE items SET stock = stock - ELT(FIELD(id, " . implode(',', $item_ids) . "), " . implode(',', $qtys) . ") WHERE id IN (" . implode(',', $item_ids) . ");";

        // Execute the SQL query
        DB::statement($sql);

        return response()->json([
            'message' => 'success create item-in and updated stock',
            'data' => $itemOut
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemOut $itemOut)
    {
        return response()->json([
            'message' => 'success get item-out',
            'data' => $itemOut->load(['operator:id,name', 'division:id,name', 'details'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemOut $itemOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemOut $itemOut)
    {
        // update stock
        $item_ids = array_column($itemOut->details->toArray(), 'item_id');
        $qtys = array_column($itemOut->details->toArray(), 'qty');
        
        // Create the SQL query dynamically
        $sql = "UPDATE items SET stock = stock + ELT(FIELD(id, " . implode(',', $item_ids) . "), " . implode(',', $qtys) . ") WHERE id IN (" . implode(',', $item_ids) . ");";

        // Execute the SQL query
        DB::statement($sql);

        $itemOut->delete();
        return response()->json([
            'message' => 'success delete item-in',
        ]);
    }
}
