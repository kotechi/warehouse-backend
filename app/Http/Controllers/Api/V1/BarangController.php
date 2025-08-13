<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Http\Resources\Api\V1\BarangResource;
use App\Events\BarangUpdated;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori', 'divisi', 'createdBy', 'updatedBy')->get();
        return BarangResource::collection($barangs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'kodegrp' => 'required|string|max:255',
            'kategori_id' => 'required',
            'status' => 'required|string|max:255',
            'main_produk' => 'nullable',
            'stock' => 'required|integer|min:0',
            'kode_qr' => 'required|string|max:255',
            'line_divisi' => 'required|string|max:255',
            'production_date' => 'required|date_format:Y-m-d',
            'user_id' => 'required',
        ]);
        

        $barang = Barang::create([
            'produk' => $request->produk,
            'kodegrp' => $request->kodegrp,
            'kategori_id' => $request->kategori_id,
            'status' => $request->status,
            'line_divisi' => $request->line_divisi,
            'kode_qr' => $request->kode_qr,
            'production_date' => $request->production_date,
            'stock_awal' => $request->stock,
            'stock_sekarang' => $request->stock,
            'created_by' => $request->user_id, // Assuming you have authentication
            'main_produk' => $request->main_produk,
        ]);

        $barangs = Barang::with('kategori', 'divisi', 'createdBy', 'updatedBy')->get();
        return BarangResource::collection($barangs);
        return response()->json([
            'message' => 'Barang created successfully',
            'data' => BarangResource::collection($barangs)
        ], 201);
    }

    public function show(string $id)
    {
        $barang = Barang::with('kategori', 'createdBy', 'updatedBy')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'kodegrp' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'status' => 'required|string|max:255',
            'line_divisi' => 'required|string|max:255',
            'kode_qr' => 'required|string|max:255|unique:barangs,kode_qr,' . $id,
            'production_date' => 'required|date_format:Y-m-d',
            'stock' => 'required|integer|min:0',
            'main_produk' => 'nullable|exists:main_produks,id',
            'user_id' => 'required',
        ]);
        
        $barang = Barang::findOrFail($id);
        $barang->update([
            'produk' => $request->produk,
            'kodegrp' => $request->kodegrp,
            'kategori_id' => $request->kategori_id,
            'status' => $request->status,
            'line_divisi' => $request->line_divisi,
            'kode_qr' => $request->kode_qr,
            'production_date' => $request->production_date,
            'stock_awal' => $request->stock,
            'stock_sekarang' => $request->stock,
            'updated_by' => $request->user_id, // Assuming you have authentication
            'main_produk' => $request->main_produk,
        ]);

        
        return response()->json([
            'message' => 'Barang updated successfully',
            'data' => new BarangResource($barang)
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        
        
        return response()->json([
            'message' => 'Barang deleted successfully'
        ]);
    }

    public function stockIn(Request $request, string $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->stock_sekarang += $request->stock;
        $barang->updated_by = auth()->id(); // Track who updated the stock
        $barang->save();


        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => new BarangResource($barang),
        ]);
    }

    public function stockOut(Request $request, string $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($id);
        
        if ($barang->stock_sekarang < $request->stock) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $barang->stock_sekarang -= $request->stock;
        $barang->updated_by = auth()->id(); // Track who updated the stock
        $barang->save();
        
        
        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => new BarangResource($barang),
        ]);
    }
}