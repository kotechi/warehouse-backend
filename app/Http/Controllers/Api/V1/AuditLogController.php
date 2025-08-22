<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\Barang;
use App\Http\Resources\Api\V1\AuditLogResource;
class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auditlogs = AuditLog::with('barang', 'user')->get();
        return AuditLogResource::collection($auditlogs);
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $auditlogs = AuditLog::findOrFail($id);
        $barang_id = $auditlogs->barang_id;
        $barang = Barang::findOrFail($id);
        

        $request->validate([
            'stock' => 'required|integer',
            'type' => 'required|string',
            'deskripsi' => 'required|string',
            'user_id' => 'integer'
        ]);

        if($request->type = "Stock In") {
            $stock_saat_ini = $barang->stock_sekarang-$auditlogs->new_values;
            return response()->json([
                "message" => "stockin",
                "data" => $stock_saat_ini
            ]);
        } else if($request->type = "Stock Out") {
            $stock_saat_ini = $barang->stock_sekarang+$auditlogs->new_values;
            return response()->json([
                "message" => "stocout",
                "data" => $stock_saat_ini
            ]);
        }
        // $auditlogs->create([
        //     'updated_by' => $request->user_id,
        //     'user_id' => $request->user_id,
        //     'type' => $request->type,
        //     'deskripsi' => $request->deskripsi,
        //     'old_values' => $auditlogs->new_values,
        //     'type' => $request->type,
        //     'barang_id' => $barang_id,
        // ]);

        // $barang->update([
        //     'stock_sekarang' => $request->  
        // ]);
        
        // You may want to update the model here as well
        // $auditlogs->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
