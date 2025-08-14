<?php

namespace App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Http\Resources\Api\V1\BarangResource;
use App\Events\BarangUpdated;
use Illuminate\Support\Facades\Config;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori', 'divisi', 'createdBy', 'updatedBy')->get();
        return BarangResource::collection($barangs);
    }

    public function store(Request $request)
    {
        // Use Validator facade instead of $request->validate()
        $validator = Validator::make($request->all(), [
            'produk' => 'required|string|max:255',
            'kodegrp' => 'required|string|max:255',
            'kategori_id' => 'required|integer', // Added exists validation
            'status' => 'required|string|max:255',
            'main_produk' => 'nullable|integer', // Added exists validation
            'stock' => 'required|integer|min:0',
            'line_divisi' => 'required|max:255',
            'production_date' => 'required', // Changed to date validation
            'created_by' => 'required|integer', // Added exists validation
        ]);

        // Fix the validation check
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $barang = Barang::create([
                'produk' => $request->produk,
                'kodegrp' => $request->kodegrp,
                'kategori_id' => $request->kategori_id,
                'status' => $request->status,
                'main_produk' => $request->main_produk,
                'stock_awal' => $request->stock,
                'stock_sekarang' => $request->stock,
                'kode_qr' => Config::get('services.frontend_url'). '/qr/' . $request->kodegrp . '/' . $request->produk,
                'line_divisi' => $request->line_divisi,
                'production_date' => $request->production_date,
                'created_by' => $request->created_by,
                'updated_by' => null,
                'deleted_at' => null,
            ]);

            return response()->json([
                'message' => 'Barang created successfully',
                'data' => $barang
            ], 201); // Changed to 201 for created resource
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Barang',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'line_divisi' => 'required|max:255',
            'kode_qr' => 'required|string|max:255|unique:barangs,kode_qr,' . $id,
            'production_date' => 'required',
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