<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['createdBy', 'category'])->get();

        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required|unique:products',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = new Product();
        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->created_by = $request->input('created_by');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('public/images');
            $product->image = $imagePath;
        }

        $product->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }






    public function show($id)
    {
        $edit_data = Product::find($id);
        return response()->json(['status' => 200, 'edit_product' => $edit_data]);

    }

    public function update(Request $request, $id)
    {
        try {
            $validator = $request->validate([
                'category_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'created_by' => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete the existing image if a new image is provided
        if ($request->hasFile('image')) {
            if ($product->image) {
                $imagePath = public_path($product->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $image = $request->file('image');
            $imagePath = $image->store('public/images');
            $product->image = $imagePath;
        }

        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->created_by = $request->input('created_by');

        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete the corresponding image if it exists
        if ($product->image) {
            $imagePath = public_path($product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}