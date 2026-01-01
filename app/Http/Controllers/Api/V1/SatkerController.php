<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Satker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatkerController extends Controller
{
    /**
     * Display a listing of satker
     */
    public function index(Request $request)
    {
        $query = Satker::with('entitas', 'unitEselonIis');

        // Filter by entitas_id if provided
        if ($request->has('entitas_id')) {
            $query->where('entitas_id', $request->entitas_id);
        }

        $satkers = $query->get();
        
        return response()->json([
            'message' => 'Satker retrieved successfully',
            'data' => $satkers
        ]);
    }

    /**
     * Store a newly created satker
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entitas_id' => 'required|exists:entitas,id',
            'kode_satker' => 'required|string|max:50|unique:satkers,kode_satker',
            'nama_satker' => 'required|string|max:255',
            'unit_eselon_i' => 'nullable|string|max:255',
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
            $satker = Satker::create($request->only([
                'entitas_id',
                'kode_satker',
                'nama_satker',
                'unit_eselon_i',
                'alamat',
                'status',
            ]));

            return response()->json([
                'message' => 'Satker created successfully',
                'data' => $satker->load('entitas')
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Satker',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified satker
     */
    public function show(string $id)
    {
        $satker = Satker::with('entitas', 'unitEselonIis', 'asets')->findOrFail($id);
        
        return response()->json([
            'message' => 'Satker retrieved successfully',
            'data' => $satker
        ]);
    }

    /**
     * Update the specified satker
     */
    public function update(Request $request, string $id)
    {
        $satker = Satker::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'entitas_id' => 'required|exists:entitas,id',
            'kode_satker' => 'required|string|max:50|unique:satkers,kode_satker,' . $id,
            'nama_satker' => 'required|string|max:255',
            'unit_eselon_i' => 'nullable|string|max:255',
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
            $satker->update($request->only([
                'entitas_id',
                'kode_satker',
                'nama_satker',
                'unit_eselon_i',
                'alamat',
                'status',
            ]));

            return response()->json([
                'message' => 'Satker updated successfully',
                'data' => $satker->load('entitas')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Satker',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified satker (soft delete)
     */
    public function destroy(string $id)
    {
        $satker = Satker::findOrFail($id);
        
        // Check if there are related unit eselon iis or asets
        if ($satker->unitEselonIis()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete satker with existing unit eselon IIs',
            ], 400);
        }

        if ($satker->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete satker with existing assets',
            ], 400);
        }

        $satker->delete();
        
        return response()->json([
            'message' => 'Satker deleted successfully'
        ]);
    }
}
