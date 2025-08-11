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
        return BarangResource::collection(Barang::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'nama_barang' => 'required|string|max:255',
            'line_divisi' => 'required',
            'kode_qr' => 'required|string|max:255|unique:barangs,kode_qr',
            'production_date' => 'required|date',
        ]);

        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'line_divisi' => $request->line_divisi,
            'kode_qr' => $request->kode_qr,
            'production_date' => $request->production_date,
            'stock_awal' => $request->stock,
            'stock_sekarang' => $request->stock,
        ]);

        event(new BarangUpdated($barang, 'created'));
        
        return response()->json([
            'message' => 'Barang created successfully',
            'data' => new BarangResource($barang)
        ], 201);
    }

    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return new BarangResource($barang);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'line_divisi' => 'required',
            'kode_qr' => 'required|string|max:255|unique:barangs,kode_qr,' . $id,
            'production_date' => 'required|date',
            'stock' => 'required|integer|min:0', 
        ]);
        
        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'line_divisi' => $request->line_divisi,
            'kode_qr' => $request->kode_qr,
            'production_date' => $request->production_date,
            'stock_awal' => $request->stock,
            'stock_sekarang' => $request->stock,
        ]);

        event(new BarangUpdated($barang, 'updated'));
        
        return response()->json([
            'message' => 'Barang updated successfully',
            'data' => new BarangResource($barang)
        ]);
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        
        event(new BarangUpdated($barang, 'deleted'));
        
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
        $barang->save();

        event(new BarangUpdated($barang, 'stock_in'));

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
        $barang->save();
        
        event(new BarangUpdated($barang, 'stock_out'));
        
        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => new BarangResource($barang),
        ]);
    }
}