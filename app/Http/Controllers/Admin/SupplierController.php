<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);
        
        $suppliers = Supplier::all();
        $suppliers->when($request->input('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        });

        $data = $suppliers->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get suppliers',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:32|unique:suppliers,name',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create($validator->validated());
        return response()->json([
            'message' => 'success create supplier',
            'data' => $supplier
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return response()->json([
            'message' => 'success get supplier',
            'data' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:32|unique:suppliers,name',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = $supplier->update($validator->validated());
        return response()->json([
            'message' => 'success create supplier',
            'data' => $supplier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json([
            'message' => 'success delete supplier'
        ]);
    }
}
