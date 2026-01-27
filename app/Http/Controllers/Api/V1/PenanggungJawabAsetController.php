<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PenanggungJawabAsetResource;
use App\Http\Resources\Api\V1\PenanggungJawabAsetCollection;
use App\Http\Requests\Api\V1\StorePenanggungJawabAsetRequest;
use App\Http\Requests\Api\V1\UpdatePenanggungJawabAsetRequest;
use App\Models\PenanggungJawabAset;
use Illuminate\Http\Request;

class PenanggungJawabAsetController extends Controller
{
    /**
     * Display a listing of penanggung jawab aset
     */
    public function index(Request $request)
    {
        $query = PenanggungJawabAset::with('user', 'unitEselonIi.satker.entitas');

        // Filter by unit_eselon_ii_id if provided
        if ($request->has('unit_eselon_ii_id')) {
            $query->where('unit_eselon_ii_id', $request->unit_eselon_ii_id);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        if ($perPage == 'all') {
            $penanggungJawabAsets = $query->get();
            return response()->json([
                'message' => 'Penanggung Jawab Aset retrieved successfully',
                'data' => PenanggungJawabAsetResource::collection($penanggungJawabAsets)
            ]);
        }

        $penanggungJawabAsets = $query->paginate($perPage);

        return response()->json([
            'message' => 'Penanggung Jawab Aset retrieved successfully',
        ])->merge((new PenanggungJawabAsetCollection($penanggungJawabAsets))->toArray($request));
    }

    /**
     * Store a newly created penanggung jawab aset
     */
    public function store(StorePenanggungJawabAsetRequest $request)
    {
        try {
            $penanggungJawabAset = PenanggungJawabAset::create($request->validated());

            return response()->json([
                'message' => 'Penanggung Jawab Aset created successfully',
                'data' => new PenanggungJawabAsetResource($penanggungJawabAset->load('unitEselonIi', 'user'))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Penanggung Jawab Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified penanggung jawab aset
     */
    public function show(string $id)
    {
        $penanggungJawabAset = PenanggungJawabAset::with('user', 'unitEselonIi.satker.entitas', 'asets')->findOrFail($id);

        return response()->json([
            'message' => 'Penanggung Jawab Aset retrieved successfully',
            'data' => new PenanggungJawabAsetResource($penanggungJawabAset)
        ]);
    }

    /**
     * Update the specified penanggung jawab aset
     */
    public function update(UpdatePenanggungJawabAsetRequest $request, string $id)
    {
        $penanggungJawabAset = PenanggungJawabAset::findOrFail($id);

        try {
            $penanggungJawabAset->update($request->validated());

            return response()->json([
                'message' => 'Penanggung Jawab Aset updated successfully',
                'data' => new PenanggungJawabAsetResource($penanggungJawabAset->load('unitEselonIi', 'user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Penanggung Jawab Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified penanggung jawab aset (soft delete)
     */
    public function destroy(string $id)
    {
        try {
            $penanggungJawabAset = PenanggungJawabAset::findOrFail($id);

            // Check if there are related asets
            if ($penanggungJawabAset->asets()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot delete penanggung jawab aset with existing assets',
                    'errors' => ['asets' => 'Penanggung jawab masih memiliki aset yang terkait']
                ], 400);
            }

            $penanggungJawabAset->delete();

            return response()->json([
                'message' => 'Penanggung Jawab Aset deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Penanggung Jawab Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore the specified soft deleted penanggung jawab aset
     */
    public function restore(string $id)
    {
        try {
            $penanggungJawabAset = PenanggungJawabAset::withTrashed()->findOrFail($id);
            $penanggungJawabAset->restore();

            return response()->json([
                'message' => 'Penanggung Jawab Aset restored successfully',
                'data' => new PenanggungJawabAsetResource($penanggungJawabAset->load('unitEselonIi', 'user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to restore Penanggung Jawab Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete the specified penanggung jawab aset
     */
    public function forceDelete(string $id)
    {
        try {
            $penanggungJawabAset = PenanggungJawabAset::withTrashed()->findOrFail($id);

            // Check if there are related asets
            if ($penanggungJawabAset->asets()->count() > 0) {
                return response()->json([
                    'message' => 'Cannot permanently delete penanggung jawab aset with existing assets',
                    'errors' => ['asets' => 'Penanggung jawab masih memiliki aset yang terkait']
                ], 400);
            }

            $penanggungJawabAset->forceDelete();

            return response()->json([
                'message' => 'Penanggung Jawab Aset permanently deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to permanently delete Penanggung Jawab Aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trashed penanggung jawab aset
     */
    public function trashed(Request $request)
    {
        $query = PenanggungJawabAset::onlyTrashed()->with('user', 'unitEselonIi.satker.entitas');

        // Filter by unit_eselon_ii_id if provided
        if ($request->has('unit_eselon_ii_id')) {
            $query->where('unit_eselon_ii_id', $request->unit_eselon_ii_id);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'LIKE', "%{$search}%")
                    ->orWhere('nip', 'LIKE', "%{$search}%")
                    ->orWhere('jabatan', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('telepon', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'deleted_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        if ($perPage == 'all') {
            $trashedPenanggungJawabAsets = $query->get();
            return response()->json([
                'message' => 'Trashed Penanggung Jawab Aset retrieved successfully',
                'data' => PenanggungJawabAsetResource::collection($trashedPenanggungJawabAsets)
            ]);
        }

        $trashedPenanggungJawabAsets = $query->paginate($perPage);

        return response()->json([
            'message' => 'Trashed Penanggung Jawab Aset retrieved successfully',
        ])->merge((new PenanggungJawabAsetCollection($trashedPenanggungJawabAsets))->toArray($request));
    }
}
