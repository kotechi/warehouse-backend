<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Http\Resources\Api\V1\BarangResource;
use App\Http\Resources\Api\V1\KategoriResource;
use App\Models\Kategori;
class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::all();
        return KategoriResource::collection($kategoris);
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
        $validatedData = $request->validate([
            'kategori' => 'required|string|max:255',
            'status' => 'required',
        ]);
        $kategori = Kategori::create($validatedData);
        return response()->json($kategori);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barangs = Barang::with(['kategori', 'divisi', 'createdBy', 'updatedBy'])->where('kategori_id', $id)->get();
        return BarangResource::collection($barangs);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'kategori' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validatedData);

        return response()->json([
            'message' => 'Kategori updated successfully',
            'data' => $kategori
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return response()->json([
            'message' => 'Kategori deleted successfully with ID: ' . $id
        ])->setStatusCode(200);
    }
}
