<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemOutRequest;
use App\Models\Employee;
use App\Models\ItemOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemOutRequest $request)
    {
        // get validated data
        $validatedData = $request->validated();

        $userNIP = auth()->user()->nip;
        $employeeID = Employee::where('nip', $userNIP)->first()->id;
        
        // directly create a new array with only the needed keys
        $data = [
            'operator_id' => $employeeID,
            'division_id' => $validatedData['division_id'],
            'total_items' => count($validatedData['items']),
        ];

        $itemOut = ItemOut::create($data);
        $itemOut->details()->createMany($validatedData['items']);

        // update stock
        $item_ids = array_column($validatedData['items'], 'item_id');
        $qtys = array_column($validatedData['items'], 'qty');

        if (empty($item_ids)) {
            // Handle the case when there are no items
            return response()->json([
                'message' => 'success create item-out',
                'data' => $itemOut
            ]);
        }

        // Prepare the SQL query
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
        $sql = "UPDATE items SET stock = stock - ELT(FIELD(id, $placeholders), " . implode(',', array_fill(0, count($qtys), '?')) . ") WHERE id IN ($placeholders)";

        // Prepare the parameters
        $params = array_merge($item_ids, $qtys, $item_ids);

        // Execute the SQL query using prepared statement
        DB::update($sql, $params);

        return response()->json([
            'message' => 'success create item-out and updated stock',
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
        $details = $itemOut->details->toArray();
        $item_ids = array_column($details, 'item_id');
        $qtys = array_column($details, 'qty');

        if (empty($item_ids)) {
            // Handle the case when there are no items
            $itemOut->delete();
            return response()->json([
                'message' => 'success delete item-out',
            ]);
        }

        // Prepare the SQL query
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
        $sql = "UPDATE items SET stock = stock + ELT(FIELD(id, $placeholders), " . implode(',', array_fill(0, count($qtys), '?')) . ") WHERE id IN ($placeholders)";

        // Prepare the parameters
        $params = array_merge($item_ids, $qtys, $item_ids);

        // Execute the SQL query using prepared statement
        DB::update($sql, $params);

        $itemOut->delete();
        return response()->json([
            'message' => 'success delete item-out',
        ]);
    }
}
