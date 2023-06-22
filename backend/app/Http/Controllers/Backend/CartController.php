<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{

    public function allAddToCart(){
        $all_carts=Cart::all();
        return response()->json(['status' => 200, 'all_carts' => $all_carts]);

    }
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::find($request->input('product_id'));

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $cartItem = new Cart();
        $cartItem->user_id = $request->input('user_id');
        $cartItem->product_id = $product->id;
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return response()->json(['message' => 'Product added to cart successfully', 'cart_item' => $cartItem], 200);
    }
    public function updateAddToCart(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $cartItem = Cart::find($id);

        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }
        $cartItem->user_id = $request->input('user_id');
        $cartItem->product_id = $request->product_id;
        $cartItem->quantity = $request->input('quantity');
        $cartItem->update();

        return response()->json(['message' => 'Cart item updated successfully', 'cart_item' => $cartItem], 200);
    }
    public function removeFromCart(Request $request, $id)
{
    $cartItem = Cart::find($id);

    if (!$cartItem) {
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    $cartItem->delete();

    return response()->json(['message' => 'Product removed from cart successfully'], 200);
}


}