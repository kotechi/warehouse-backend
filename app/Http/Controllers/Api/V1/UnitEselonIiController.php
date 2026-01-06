<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UnitEselonIi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitEselonIiController extends Controller
{
    /**
     * Display a listing of unit eselon ii
     */
    public function index(Request $request)
    {
        $query = UnitEselonIi::with('satker.entitas');

        // Filter by satker_id if provided
        if ($request->has('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        $unitEselonIis = $query->get();
        
        return response()->json([
            'message' => 'Unit Eselon II retrieved successfully',
            'data' => $unitEselonIis
        ]);
    }

    /**
     * Store a newly created unit eselon ii
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'satker_id' => 'required|exists:satkers,id',
            'kode_unit' => 'required|string|max:50|unique:unit_eselon_iis,kode_unit',
            'nama_unit' => 'required|string|max:255',
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
            $unitEselonIi = UnitEselonIi::create($request->only([
                'satker_id',
                'kode_unit',
                'nama_unit',
                'deskripsi',
                'status',
            ]));

            return response()->json([
                'message' => 'Unit Eselon II created successfully',
                'data' => $unitEselonIi->load('satker')
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Unit Eselon II',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified unit eselon ii
     */
    public function show(string $id)
    {
        $unitEselonIi = UnitEselonIi::with('satker.entitas', 'penanggungJawabAsets', 'asets')->findOrFail($id);
        
        return response()->json([
            'message' => 'Unit Eselon II retrieved successfully',
            'data' => $unitEselonIi
        ]);
    }

    /**
     * Update the specified unit eselon ii
     */
    public function update(Request $request, string $id)
    {
        $unitEselonIi = UnitEselonIi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'satker_id' => 'required|exists:satkers,id',
            'kode_unit' => 'required|string|max:50|unique:unit_eselon_iis,kode_unit,' . $id,
            'nama_unit' => 'required|string|max:255',
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
            $unitEselonIi->update($request->only([
                'satker_id',
                'kode_unit',
                'nama_unit',
                'deskripsi',
                'status',
            ]));

            return response()->json([
                'message' => 'Unit Eselon II updated successfully',
                'data' => $unitEselonIi->load('satker')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Unit Eselon II',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified unit eselon ii (soft delete)
     */
    public function destroy(string $id)
    {
        $unitEselonIi = UnitEselonIi::findOrFail($id);
        
        // Check if there are related penanggung jawab asets or asets
        if ($unitEselonIi->penanggungJawabAsets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete unit eselon II with existing penanggung jawab',
            ], 400);
        }

        if ($unitEselonIi->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete unit eselon II with existing assets',
            ], 400);
        }

        $unitEselonIi->delete();
        
        return response()->json([
            'message' => 'Unit Eselon II deleted successfully'
        ]);
    }
}
