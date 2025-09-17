<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BarangController;
use App\Http\Controllers\Api\V1\NotifikasiController;



Route::group(['prefix' => 'v1' ,'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::apiResource('barang', BarangController::class)->middleware('auth:sanctum');
    Route::post('barang/{id}/stock-in', [BarangController::class, 'stockIn'])->middleware('auth:sanctum');
    Route::post('barang/{id}/stock-out', [BarangController::class, 'stockOut'])->middleware('auth:sanctum');
    Route::get('barang/{id}/stock', [BarangController::class, 'stockDetail']);
    
    Route::get('stock', [BarangController::class, 'listStock']);
    Route::apiResource('auditlog', AuditLogController::class)->middleware('auth:sanctum');
    Route::apiResource('kategori', KategoriController::class)->middleware('auth:sanctum');
    Route::apiResource('divisi', DivisiController::class)->middleware('auth:sanctum');
    Route::apiResource('jabatan', JabatanController::class)->middleware('auth:sanctum');
    Route::apiResource('activitylog', ActivityLogController::class)->middleware('auth:sanctum');
    Route::apiResource('user', UserController::class)->middleware('auth:sanctum');
    
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('notifikasi/{jumlah_minimum}', [NotifikasiController::class, 'notifikasi'])->middleware('auth:sanctum');

    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('me', [AuthController::class, 'update'])->middleware('auth:sanctum');
});