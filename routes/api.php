<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::group(['prefix' => 'v1' ,'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('barang', BarangController::class);
    Route::post('barang/{id}/stock-in', [BarangController::class, 'stockIn']);
    Route::post('barang/{id}/stock-out', [BarangController::class, 'stockOut']);

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});