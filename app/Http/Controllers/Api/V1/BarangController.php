<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\AuditLog;
use App\Models\Kategori;
use App\Http\Resources\Api\V1\BarangResource;
use App\Events\BarangUpdated;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('kategori', 'divisi', 'createdBy', 'updatedBy')->get();
        return BarangResource::collection($barangs);
    }

    public function show(string $id)
    {
        $barang = Barang::with('kategori', 'divisi', 'createdBy', 'updatedBy')
                        ->findOrFail($id);
        return new BarangResource($barang);
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

        $newid = Barang::max('id') + 1; // Get the next ID for the new Barang
        try {
            $barang = Barang::create([
                'produk' => $request->produk,
                'kodegrp' => $request->kodegrp,
                'kategori_id' => $request->kategori_id,
                'status' => $request->status,
                'main_produk' => $request->main_produk,
                'stock_awal' => $request->stock,
                'stock_sekarang' => $request->stock,
                'kode_qr' => Config::get('services.frontend_url'). '/qr/' . $newid,
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

    public function update(Request $request, string $id)
    {
    
        $validator = Validator::make($request->all(), [
            'produk' => 'required|string|max:255',
            'kodegrp' => 'required|string|max:255',
            'kategori_id' => 'required|integer',
            'status' => 'required|string|max:255',
            'production_date' => 'required',
            'user_id' => 'required|integer',
        ]);
     
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }
        $barang = Barang::findOrFail($id);
        $barang->update([
            'produk' => $request->produk,
            'kodegrp' => $request->kodegrp,
            'kategori_id' => $request->kategori_id,
            'status' => $request->status,
            'production_date' => $request->production_date,
            'updated_by' => $request->user_id,  
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
            'type' => 'required|string',
            'deskripsi' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        $barang = Barang::findOrFail($id);
        $auditlog = AuditLog::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'barang_id' => $id,
            'deskripsi' => $request->deskripsi,
            'old_values' => $barang->stock_sekarang,
            'new_values' => $barang->stock_sekarang + $request->stock,
            'input_values' => $request->stock
        ]);
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
            'type' => 'required|string',
            'deskripsi' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        $barang = Barang::findOrFail($id);
        
        if ($barang->stock_sekarang < $request->stock) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        // Store old stock value before updating
        $oldStock = $barang->stock_sekarang;
        
        // Update stock once
        $barang->stock_sekarang -= $request->stock;
        $barang->updated_by = auth()->id();
        $barang->save();
        
        // Create audit log with correct values
        $auditlog = AuditLog::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'barang_id' => $id,
            'deskripsi' => $request->deskripsi,
            'old_values' => $oldStock,
            'new_values' => $barang->stock_sekarang,
            'input_values' => $request->stock
        ]);
        
        return response()->json([
            'message' => 'Stock updated successfully',
            'data' => new BarangResource($barang),
        ]);
    }
}