<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntitasController extends Controller
{
    /**
     * Display a listing of entitas
     */
    public function index()
    {
        $entitas = Entitas::with('satkers')->get();
        
        return response()->json([
            'message' => 'Entitas retrieved successfully',
            'data' => $entitas
        ]);
    }

    /**
     * Store a newly created entitas
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_entitas' => 'required|string|max:50|unique:entitas,kode_entitas',
            'nama_entitas' => 'required|string|max:255',
            'jenis_entitas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $entitas = Entitas::create($request->only([
                'kode_entitas',
                'nama_entitas',
                'jenis_entitas',
                'alamat',
                'status',
            ]));

            return response()->json([
                'message' => 'Entitas created successfully',
                'data' => $entitas
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Entitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified entitas
     */
    public function show(string $id)
    {
        $entitas = Entitas::with('satkers', 'asets')->findOrFail($id);
        
        return response()->json([
            'message' => 'Entitas retrieved successfully',
            'data' => $entitas
        ]);
    }

    /**
     * Update the specified entitas
     */
    public function update(Request $request, string $id)
    {
        $entitas = Entitas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_entitas' => 'required|string|max:50|unique:entitas,kode_entitas,' . $id,
            'nama_entitas' => 'required|string|max:255',
            'jenis_entitas' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $entitas->update($request->only([
                'kode_entitas',
                'nama_entitas',
                'jenis_entitas',
                'alamat',
                'status',
            ]));

            return response()->json([
                'message' => 'Entitas updated successfully',
                'data' => $entitas
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Entitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified entitas (soft delete)
     */
    public function destroy(string $id)
    {
        $entitas = Entitas::findOrFail($id);
        
        // Check if there are related satkers or asets
        if ($entitas->satkers()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete entitas with existing satkers',
            ], 400);
        }

        if ($entitas->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete entitas with existing assets',
            ], 400);
        }

        $entitas->delete();
        
        return response()->json([
            'message' => 'Entitas deleted successfully'
        ]);
    }
}
