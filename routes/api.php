<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VariantController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('product-list', [ProductController::class, 'index']);
Route::post('product-list', [ProductController::class, 'upload']);
Route::prefix('products')->group(
    
    function () {
        Route::put('/update', [ProductController::class, 'update']);
        Route::delete('/delete', [ProductController::class, 'delete']);
        Route::post('/search', [ProductController::class, 'search']);

        Route::prefix('variants')->group(function () {
            Route::post('/', [VariantController::class, 'store']);
            Route::put('/update', [VariantController::class, 'update']);
            Route::delete('/delete', [VariantController::class, 'delete']);
        });
    }
);


