<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DetailKategoriAset;
use App\Models\SubkategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailKategoriAsetController extends Controller
{
    /**
     * Display a listing of detail kategori aset with filters
     */
    public function index(Request $request)
    {
        $query = DetailKategoriAset::with([
            'subkategoriAset.kategoriAset'
        ]);

        // Filter by kategori_aset_id (through relationship)
        if ($request->has('kategori_aset_id')) {
            $query->whereHas('subkategoriAset', function ($q) use ($request) {
                $q->where('kategori_aset_id', $request->kategori_aset_id);
            });
        }

        // Filter by subkategori_aset_id
        if ($request->has('subkategori_aset_id')) {
            $query->where('subkategori_aset_id', $request->subkategori_aset_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_detail_kategori', 'LIKE', "%{$search}%")
                  ->orWhere('kode_detail_kategori', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $detailKategoriAsets = $query->paginate($perPage);
        
        return response()->json([
            'message' => 'Detail Kategori Aset retrieved successfully',
            'data' => $detailKategoriAsets
        ]);
    }

    /**
     * Get dropdown list of detail kategori aset
     */
    public function dropdown(Request $request)
    {
        $search = $request->input('search', '');
        $kategoriAsetId = $request->input('kategori_aset_id');
        $subkategoriAsetId = $request->input('subkategori_aset_id');
        
        $query = DetailKategoriAset::where('status', 'aktif')
            ->with(['subkategoriAset.kategoriAset']);

        // Filter by kategori_aset_id (through relationship)
        if ($kategoriAsetId) {
            $query->whereHas('subkategoriAset', function ($q) use ($kategoriAsetId) {
                $q->where('kategori_aset_id', $kategoriAsetId);
            });
        }

        // Filter by subkategori_aset_id
        if ($subkategoriAsetId) {
            $query->where('subkategori_aset_id', $subkategoriAsetId);
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_detail_kategori', 'LIKE', "%{$search}%")
                  ->orWhere('kode_detail_kategori', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy('nama_detail_kategori')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_detail_kategori . ' (' . $item->kode_detail_kategori . ')',
                    'subkategori_aset_id' => $item->subkategori_aset_id,
                    'subkategori_nama' => $item->subkategoriAset->nama_subkategori ?? null,
                    'kategori_aset_id' => $item->subkategoriAset->kategori_aset_id ?? null,
                    'kategori_nama' => $item->subkategoriAset->kategoriAset->nama_kategori ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created detail kategori aset
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subkategori_aset_id' => 'required|exists:subkategori_asets,id',
            'kode_detail_kategori' => 'required|string|max:50|unique:detail_kategori_asets,kode_detail_kategori',
            'nama_detail_kategori' => 'required|string|max:255',
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
            $detailKategoriAset = DetailKategoriAset::create([
                'subkategori_aset_id' => $request->subkategori_aset_id,
                'kode_detail_kategori' => $request->kode_detail_kategori,
                'nama_detail_kategori' => $request->nama_detail_kategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            $detailKategoriAset->load('subkategoriAset.kategoriAset');

            return response()->json([
                'message' => 'Detail Kategori Aset created successfully',
                'data' => $detailKategoriAset
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Detail Kategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified detail kategori aset
     */
    public function show(string $id)
    {
        $detailKategoriAset = DetailKategoriAset::with([
            'subkategoriAset.kategoriAset',
            'asets'
        ])->findOrFail($id);
        
        return response()->json([
            'message' => 'Detail Kategori Aset retrieved successfully',
            'data' => $detailKategoriAset
        ]);
    }

    /**
     * Update the specified detail kategori aset
     */
    public function update(Request $request, string $id)
    {
        $detailKategoriAset = DetailKategoriAset::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'subkategori_aset_id' => 'required|exists:subkategori_asets,id',
            'kode_detail_kategori' => 'required|string|max:50|unique:detail_kategori_asets,kode_detail_kategori,' . $id,
            'nama_detail_kategori' => 'required|string|max:255',
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
            $detailKategoriAset->update([
                'subkategori_aset_id' => $request->subkategori_aset_id,
                'kode_detail_kategori' => $request->kode_detail_kategori,
                'nama_detail_kategori' => $request->nama_detail_kategori,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
            ]);

            $detailKategoriAset->load('subkategoriAset.kategoriAset');

            return response()->json([
                'message' => 'Detail Kategori Aset updated successfully',
                'data' => $detailKategoriAset
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Detail Kategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified detail kategori aset
     */
    public function destroy(string $id)
    {
        try {
            $detailKategoriAset = DetailKategoriAset::findOrFail($id);
            
            // Check if detail kategori has related assets
            if ($detailKategoriAset->asets()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete Detail Kategori Aset. It has related assets.',
                ], 409);
            }
            
            $detailKategoriAset->delete();

            return response()->json([
                'message' => 'Detail Kategori Aset deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Detail Kategori Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
