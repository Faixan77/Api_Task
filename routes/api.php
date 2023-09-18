<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/customers', [CustomerController::class, 'create']);

Route::post('/categories', [CategoryController::class, 'create']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::post('/products', [ProductController::class, 'create']);
Route::get('/products', [ProductController::class, 'index']);


Route::post('/sales', [SaleController::class, 'create']);

Route::put('/sales/{saleId}', [SaleController::class, 'update']);

Route::delete('/sales/{saleId}',  [SaleController::class, 'destroy']);


Route::get('/sales/{id}/invoice', [SaleController::class, 'generateInvoice']);
