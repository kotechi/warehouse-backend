<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BarangController;
use App\Http\Controllers\Api\V1\NotifikasiController;
use App\Http\Controllers\Api\V1\DropdownController;
use App\Http\Controllers\Api\V1\KategoriAsetController;
use App\Http\Controllers\Api\V1\SubkategoriAsetController;
use App\Http\Controllers\Api\V1\DetailKategoriAsetController;
use App\Http\Controllers\Api\V1\EntitasController;
use App\Http\Controllers\Api\V1\SatkerController;
use App\Http\Controllers\Api\V1\UnitEselonIiController;
use App\Http\Controllers\Api\V1\PenanggungJawabAsetController;
use App\Http\Controllers\AsetController;


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::post('login', [AuthController::class, 'login']);

    // Dropdown Routes for Aset CRUD
    Route::prefix('dropdown')->middleware('auth:sanctum')->group(function () {
        Route::get('kategori-aset', [DropdownController::class, 'kategoriAset']);
        Route::get('subkategori-aset', [DropdownController::class, 'subkategoriAset']);
        Route::get('detail-kategori-aset', [DropdownController::class, 'detailKategoriAset']);
        Route::get('entitas', [DropdownController::class, 'entitas']);
        Route::get('satker', [DropdownController::class, 'satker']);
        Route::get('unit-eselon-ii', [DropdownController::class, 'unitEselonIi']);
        Route::get('penanggung-jawab-aset', [DropdownController::class, 'penanggungJawabAset']);
        Route::get('mata-uang', [DropdownController::class, 'mataUang']);
        Route::get('kondisi-fisik', [DropdownController::class, 'kondisiFisik']);
        Route::get('status-aset', [DropdownController::class, 'statusAset']);
        Route::get('metode-penyusutan', [DropdownController::class, 'metodePenyusutan']);
        Route::get('satuan', [DropdownController::class, 'satuan']);
    });

    // Detail Kategori Aset Routes
    Route::prefix('detail-kategori-aset')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [DetailKategoriAsetController::class, 'index']); // List with filters
        Route::get('/dropdown', [DetailKategoriAsetController::class, 'dropdown']); // Dropdown
        Route::post('/', [DetailKategoriAsetController::class, 'store']); // Create
        Route::get('/{id}', [DetailKategoriAsetController::class, 'show']); // Detail
        Route::put('/{id}', [DetailKategoriAsetController::class, 'update']); // Update
        Route::delete('/{id}', [DetailKategoriAsetController::class, 'destroy']); // Delete
    });

    Route::prefix('aset')->group(function () {
        // CRUD Aset
        Route::get('/', [AsetController::class, 'index']); // List all assets with filters
        Route::post('/', [AsetController::class, 'store']); // Create new asset
        Route::get('/{id}', [AsetController::class, 'show']); // Get asset detail
        Route::put('/{id}', [AsetController::class, 'update']); // Update asset
        Route::delete('/{id}', [AsetController::class, 'destroy']); // Soft delete asset

        // Penyusutan
        Route::get('/{id}/penyusutan', [AsetController::class, 'getPenyusutan']); // Get depreciation history

        // Pemeliharaan
        Route::get('/{id}/maintenance', [AsetController::class, 'getMaintenance']); // Get maintenance history
        Route::post('/{id}/pemeliharaan', [AsetController::class, 'addPemeliharaan']); // Add maintenance record

        // Penghapusan/Pemindahtanganan
        Route::post('/{id}/disposal', [AsetController::class, 'initiateDisposal']); // Initiate disposal/transfer

        // Statistics & Warnings
        Route::get('/statistics/summary', [AsetController::class, 'getStatistics']); // Get asset statistics
        Route::get('/warnings/near-expiration', [AsetController::class, 'getAssetsNearExpiration']); // Get assets near expiration
    })->middleware('auth:sanctum');
    Route::apiResource('barang', BarangController::class)->middleware('auth:sanctum');
    Route::post('barang/{id}/stock-in', [BarangController::class, 'stockIn'])->middleware('auth:sanctum');
    Route::post('barang/{id}/stock-out', [BarangController::class, 'stockOut'])->middleware('auth:sanctum');
    Route::get('barang/{id}/stock', [BarangController::class, 'stockDetail']);

    Route::get('stock', [BarangController::class, 'listStock']);
    Route::apiResource('auditlog', AuditLogController::class)->middleware('auth:sanctum');
    Route::apiResource('kategori', KategoriController::class)->middleware('auth:sanctum');
    Route::apiResource('kategori-aset', KategoriAsetController::class)->middleware('auth:sanctum');
    Route::apiResource('subkategori-aset', SubkategoriAsetController::class)->middleware('auth:sanctum');
    Route::apiResource('divisi', DivisiController::class)->middleware('auth:sanctum');
    Route::apiResource('jabatan', JabatanController::class)->middleware('auth:sanctum');
    Route::apiResource('activitylog', ActivityLogController::class)->middleware('auth:sanctum');
    Route::apiResource('user', UserController::class)->middleware('auth:sanctum');

    // Master Data Routes
    Route::apiResource('entitas', EntitasController::class)->middleware('auth:sanctum');
    Route::apiResource('satker', SatkerController::class)->middleware('auth:sanctum');
    Route::apiResource('unit-eselon-ii', UnitEselonIiController::class)->middleware('auth:sanctum');

    // Penanggung Jawab Aset Routes
    Route::prefix('penanggung-jawab-aset')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [PenanggungJawabAsetController::class, 'index']); // List all
        Route::post('/', [PenanggungJawabAsetController::class, 'store']); // Create new
        Route::get('/trashed', [PenanggungJawabAsetController::class, 'trashed']); // List trashed
        Route::get('/{id}', [PenanggungJawabAsetController::class, 'show']); // Show detail
        Route::put('/{id}', [PenanggungJawabAsetController::class, 'update']); // Update
        Route::patch('/{id}', [PenanggungJawabAsetController::class, 'update']); // Update
        Route::delete('/{id}', [PenanggungJawabAsetController::class, 'destroy']); // Soft delete
        Route::patch('/{id}/restore', [PenanggungJawabAsetController::class, 'restore']); // Restore
        Route::delete('/{id}/force', [PenanggungJawabAsetController::class, 'forceDelete']); // Force delete
    });

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('notifikasi/{jumlah_minimum}', [NotifikasiController::class, 'notifikasi'])->middleware('auth:sanctum');

    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('me', [AuthController::class, 'update'])->middleware('auth:sanctum');
});
