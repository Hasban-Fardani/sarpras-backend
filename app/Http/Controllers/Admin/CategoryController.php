<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // get page and per_page
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        // get all categories
        $data = Category::query();

        // search by name
        $data->when($request->search, function ($data) use ($request) {
            $data->where(function ($query) use ($request) {
                // select * from categories where name like %search%
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        });

        $data->orderBy('created_at', 'desc');

        // paginate
        $data = $data->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success get all categories',
            ...$data->toArray()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate name, ensure its unique
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors() 
            ], 422);
        }

        // create new category
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'success create new category',
            'data' => $category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'message' => 'success get category',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // validate name, ensure its unique
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'errors' => $validator->errors() 
            ], 422);
        }

        // update category
        $category->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'success update category',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'success delete category'
        ]);
    }
}
