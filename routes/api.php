<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::group(['prefix' => 'v1' ,'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('barang', BarangController::class)->middleware('auth:sanctum');
    Route::post('barang/{id}/stock-in', [BarangController::class, 'stockIn']);
    Route::post('barang/{id}/stock-out', [BarangController::class, 'stockOut']);

    Route::apiResource('auditlog', AuditLogController::class)->middleware('auth:sanctum');
    Route::apiResource('kategori', KategoriController::class)->middleware('auth:sanctum');
    Route::apiResource('divisi', DivisiController::class)->middleware('auth:sanctum');
    Route::apiResource('jabatan', JabatanController::class)->middleware('auth:sanctum');
    Route::apiResource('user', UserController::class)->middleware('auth:sanctum');
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});