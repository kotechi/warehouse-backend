<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatan = Jabatan::all();
        return response()->json([
            'message' => 'berhasil fetch',
            'data' => $jabatan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'string|required|max:255'
        ]);
        $jabatan = Jabatan::create([
            'jabatan' => $request->jabatan
        ]);
        return response()->json([
            'message' => 'berhasil tambah jabatan',
            'data' => $jabatan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json([
            'message' => 'berhasil',
            'data' => $jabatan
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'jabatan' => 'string|required'
        ]);
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'jabatan' => $request->jabatan
        ]);
        return response()->json([
            'message' => 'berhasil edit',
            'data' => $jabatan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jabatan = Jabatan::fundOrFail($id);
        $jabatan->delete();
        return response()->json([
            'message' => 'berhasil hapus'
        ]);
    }
}
