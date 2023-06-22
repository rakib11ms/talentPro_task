<?php

use App\Http\Controllers\Backend\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::resource('/product', ProductController::class);
Route::resource('/category', CategoryController::class);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::put('/update-add-to-cart/{id}', [CartController::class, 'updateAddToCart']);
Route::delete('/remove-add-to-cart/{id}', [CartController::class, 'removeFromCart']);
Route::get('/all-add-to-cart', [CartController::class, 'allAddToCart']);
Route::post('/add-order', [OrderController::class, 'createOrder']);


