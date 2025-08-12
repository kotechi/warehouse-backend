<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Divisi; 

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisis = Divisi::all();
        return response()->json($divisis);
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
            'kodedivisi' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'status' => 'required',
        ]);
        $divisi = Divisi::create($validatedData);
        return response()->json($divisi, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $divisi = Divisi::findOrFail($id);
        return response()->json($divisi);
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
            'kodedivisi' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'short' => 'nullable|string|max:50',
            'status' => 'required',
        ]);
        $divisi = Divisi::findOrFail($id);
        $divisi->update($validatedData);
        return response()->json($divisi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();
        return response()->json(['message' => 'Divisi deleted successfully']);
    }
}
