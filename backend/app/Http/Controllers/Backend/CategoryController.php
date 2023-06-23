<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('createdBy')->get();

        return response()->json([
            'status'=>200,
            'categories'=>$categories
        ]);
    
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->created_by = $request->input('created_by');
        $category->save();

        return response()->json(['message' => 'Category created successfully', 'category' => $category]);
    }
        
public function show($id){
    $edit_data=Category::find($id);
    return response()->json(['status' =>200, 'edit_data' => $edit_data]);

}
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->name = $request->input('name');
        $category->created_by = $request->input('created_by');
        $category->save();

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully','status'=>200]);
    }
}
