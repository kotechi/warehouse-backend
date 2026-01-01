<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawabAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $penanggungJawabAsets = $query->get();
        
        return response()->json([
            'message' => 'Penanggung Jawab Aset retrieved successfully',
            'data' => $penanggungJawabAsets
        ]);
    }

    /**
     * Store a newly created penanggung jawab aset
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'unit_eselon_ii_id' => 'required|exists:unit_eselon_iis,id',
            'nama_pic' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:150',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $penanggungJawabAset = PenanggungJawabAset::create($request->only([
                'user_id',
                'unit_eselon_ii_id',
                'nama_pic',
                'nip',
                'jabatan',
                'telepon',
                'email',
                'status',
            ]));

            return response()->json([
                'message' => 'Penanggung Jawab Aset created successfully',
                'data' => $penanggungJawabAset->load('unitEselonIi', 'user')
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
            'data' => $penanggungJawabAset
        ]);
    }

    /**
     * Update the specified penanggung jawab aset
     */
    public function update(Request $request, string $id)
    {
        $penanggungJawabAset = PenanggungJawabAset::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'unit_eselon_ii_id' => 'required|exists:unit_eselon_iis,id',
            'nama_pic' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:150',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $penanggungJawabAset->update($request->only([
                'user_id',
                'unit_eselon_ii_id',
                'nama_pic',
                'nip',
                'jabatan',
                'telepon',
                'email',
                'status',
            ]));

            return response()->json([
                'message' => 'Penanggung Jawab Aset updated successfully',
                'data' => $penanggungJawabAset->load('unitEselonIi', 'user')
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
        $penanggungJawabAset = PenanggungJawabAset::findOrFail($id);
        
        // Check if there are related asets
        if ($penanggungJawabAset->asets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete penanggung jawab aset with existing assets',
            ], 400);
        }

        $penanggungJawabAset->delete();
        
        return response()->json([
            'message' => 'Penanggung Jawab Aset deleted successfully'
        ]);
    }
}
