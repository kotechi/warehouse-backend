<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\User;
use App\Http\Resources\Api\V1\AuditLogResource;
class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auditlogs = AuditLog::with([
            'barang',
            'user.divisi',
            'user.jabatan'
        ])->get();

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
        $auditlog = AuditLog::with([
            'barang',
            'user.divisi',
            'user.jabatan'
        ])->findOrFail($id);

        return new AuditLogResource($auditlog);
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
        $auditlog = AuditLog::findOrFail($id);
        $barang = Barang::findOrFail($auditlog->barang_id);

        $request->validate([
            'stock' => 'required|integer',
            'type' => 'required|string|in:Stock In,Stock Out',
            'deskripsi' => 'required|string',
            'user_id' => 'required|integer'
        ]);

        if ($request->type === "Stock In") {
            $newStock = $barang->stock_sekarang + $request->stock;
        } else { // Stock Out
            $newStock = $barang->stock_sekarang - $request->stock;
        }

        // update barang
        $barang->update([
            'stock_sekarang' => $newStock
        ]);

        // update audit log
        $auditlog->create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'deskripsi' => $request->deskripsi,
            'old_values' => $auditlog->new_values,
            'new_values' => $request->stock,
            'barang_id' => $auditlog->barang_id
        ]);

        return response()->json([
            "message" => "Stock updated",
            "stock" => $newStock,
            "auditlog" => $auditlog
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
