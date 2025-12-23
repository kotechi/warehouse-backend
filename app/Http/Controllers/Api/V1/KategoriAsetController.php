<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriAsetController extends Controller
{
    /**
     * Display a listing of kategori aset
     */
    public function index()
    {
        $kategoriAsets = KategoriAset::with('subkategoriAsets')->get();
        
        return response()->json([
            'message' => 'Kategori Aset retrieved successfully',
            'data' => $kategoriAsets
        ]);
    }

    /**
     * Store a newly created kategori aset
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kategori' => 'required|string|max:255|unique:kategori_asets,kode_kategori',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategoriAset = KategoriAset::create([
                'kode_kategori' => $request->kode_kategori,
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'Kategori Aset created successfully',
                'data' => $kategoriAset
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Kategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified kategori aset
     */
    public function show(string $id)
    {
        $kategoriAset = KategoriAset::with('subkategoriAsets', 'asets')->findOrFail($id);
        
        return response()->json([
            'message' => 'Kategori Aset retrieved successfully',
            'data' => $kategoriAset
        ]);
    }

    /**
     * Update the specified kategori aset
     */
    public function update(Request $request, string $id)
    {
        $kategoriAset = KategoriAset::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_kategori' => 'required|string|max:255|unique:kategori_asets,kode_kategori,' . $id,
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategoriAset->update([
                'kode_kategori' => $request->kode_kategori,
                'nama_kategori' => $request->nama_kategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            return response()->json([
                'message' => 'Kategori Aset updated successfully',
                'data' => $kategoriAset
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Kategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified kategori aset (soft delete)
     */
    public function destroy(string $id)
    {
        $kategoriAset = KategoriAset::findOrFail($id);
        
        // Check if there are related subkategori or aset
        if ($kategoriAset->subkategoriAsets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete kategori aset with existing subkategori',
            ], 400);
        }

        if ($kategoriAset->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete kategori aset with existing assets',
            ], 400);
        }

        $kategoriAset->delete();
        
        return response()->json([
            'message' => 'Kategori Aset deleted successfully'
        ]);
    }
}
