<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubkategoriAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubkategoriAsetController extends Controller
{
    /**
     * Display a listing of subkategori aset
     */
    public function index(Request $request)
    {
        $query = SubkategoriAset::with('kategoriAset');

        // Filter by kategori_aset_id if provided
        if ($request->has('kategori_aset_id')) {
            $query->where('kategori_aset_id', $request->kategori_aset_id);
        }

        $subkategoriAsets = $query->get();
        
        return response()->json([
            'message' => 'Subkategori Aset retrieved successfully',
            'data' => $subkategoriAsets
        ]);
    }

    /**
     * Store a newly created subkategori aset
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_aset_id' => 'required|exists:kategori_asets,id',
            'nama_subkategori' => 'required|string|max:255',
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
            $subkategoriAset = SubkategoriAset::create([
                'kategori_aset_id' => $request->kategori_aset_id,
                'nama_subkategori' => $request->nama_subkategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            $subkategoriAset->load('kategoriAset');

            return response()->json([
                'message' => 'Subkategori Aset created successfully',
                'data' => $subkategoriAset
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Subkategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified subkategori aset
     */
    public function show(string $id)
    {
        $subkategoriAset = SubkategoriAset::with('kategoriAset', 'detailKategoriAsets', 'asets')->findOrFail($id);
        
        return response()->json([
            'message' => 'Subkategori Aset retrieved successfully',
            'data' => $subkategoriAset
        ]);
    }

    /**
     * Update the specified subkategori aset
     */
    public function update(Request $request, string $id)
    {
        $subkategoriAset = SubkategoriAset::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kategori_aset_id' => 'required|exists:kategori_asets,id',
            'nama_subkategori' => 'required|string|max:255',
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
            $subkategoriAset->update([
                'kategori_aset_id' => $request->kategori_aset_id,
                'nama_subkategori' => $request->nama_subkategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            $subkategoriAset->load('kategoriAset');

            return response()->json([
                'message' => 'Subkategori Aset updated successfully',
                'data' => $subkategoriAset
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Subkategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subkategori aset (soft delete)
     */
    public function destroy(string $id)
    {
        $subkategoriAset = SubkategoriAset::findOrFail($id);
        
        // Check if there are related detail kategori or aset
        if ($subkategoriAset->detailKategoriAsets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete subkategori aset with existing detail kategori',
            ], 400);
        }

        if ($subkategoriAset->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete subkategori aset with existing assets',
            ], 400);
        }

        $subkategoriAset->delete();
        
        return response()->json([
            'message' => 'Subkategori Aset deleted successfully'
        ]);
    }
}
