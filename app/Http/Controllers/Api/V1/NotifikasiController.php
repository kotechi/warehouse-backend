<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotifikasiResource;
use Illuminate\Http\Request;
use App\Models\Barang;


class NotifikasiController extends Controller
{
    public function notifikasi(int $jumlah_minimum) {
        $lowStockItems = Barang::where('stock_sekarang', '<', $jumlah_minimum)
            ->with(['kategori', 'divisi'])
            ->get();

        if ($lowStockItems->isEmpty()) {
            return response()->json([
                'message' => "Tidak ada barang dengan stock dibawah {$jumlah_minimum}"
            ]);
        }

        return response()->json([
            'message' => "Data barang dengan stock dibawah {$jumlah_minimum}",
            'total' => $lowStockItems->count(),
            'data' => NotifikasiResource::collection($lowStockItems)
        ]);
    }
}
