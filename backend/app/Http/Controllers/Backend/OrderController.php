<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class OrderController extends Controller
{
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|exists:users,id',
    //         'order_number' => 'nullable',
    //         'total_amount' => 'required|numeric|min:0',
    //         'shipping_address' => 'required',
    //         'billing_address' => 'required',
    //         'status' => 'nullable',
    //         'payment_status' => 'nullable',
    //         'payment_method' => 'required',
    //         'products' => 'required|array',
    //         'products.*.product_id' => 'required|exists:products,id',
    //         'products.*.quantity' => 'required|integer|min:1',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $order = Order::create($request->all());

    //     // Associate products and quantities with the order
    //     $products = $request->input('products');
    //     foreach ($products as $product) {
    //         $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
    //     }

    //     return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    // }

    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'shipping_address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user_id = $request->input('user_id');
        $shipping_address = $request->input('shipping_address');

        // Retrieve the cart items for the user
        $cartItems = Cart::where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // Create the order
        $order = new Order();

        $prefix = 'ORDER';
        $randomDigits = mt_rand(100000, 999999);
        $uniqueNumber = $prefix . $randomDigits;
        // Associate the cart items with the order
        foreach ($cartItems as $cartItem) {
            // $order->cartItems()->attach($cartItem->product_id, ['quantity' => $cartItem->quantity]);
            $order->order_number = $uniqueNumber;
            $order->user_id = $user_id;
            $order->shipping_address = $shipping_address;
            $order->product_id = $cartItem->product_id;
            $order->quantity = $cartItem->quantity;
            $totalAmount = 0;
            $product = Product::find($cartItem->product_id);
            if ($product) {
                $totalAmount += $product->price * $cartItem->quantity;
                $order->total_amount = $totalAmount;

            }


        }
        $order->save();

        // Clear the user's cart
        // Cart::where('user_id', $user_id)->delete();

        return response()->json(['message' => 'Order created successfully', 'order' => $order, 'status' => 200]);
    }

}