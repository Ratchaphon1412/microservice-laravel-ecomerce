<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

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
Route::get('/product/format', [ProductController::class, 'format']);
Route::apiResource('/product', ProductController::class);
Route::post('/product/add-color/{product}', [ProductController::class, 'addColor']);

Route::get('/product/get-stock/{product_color}', [ProductController::class, 'getStock']);
Route::post('/product/store-stock/{product_color}', [ProductController::class, 'storeStock']);
Route::put('/product/add-stock/{product_color}', [ProductController::class, 'addStock']);
Route::put('/product/reduce-stock/{product_color}', [ProductController::class, 'reduceStock']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});