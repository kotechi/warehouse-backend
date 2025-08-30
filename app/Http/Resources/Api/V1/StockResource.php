<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'barangId'=> $this->barang_id,
            'stock'=> $this->stock,
            'keterangan'=> $this->keterangan,
            'kodeQr'=> $this->kode_qr,
            'productionDate'=> $this->production_date,
            'type'=> $this->type,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'jabatan' => $this->user->jabatan
                ? [
                    'id' => $this->user->jabatan->id,
                    'name' => $this->user->jabatan->jabatan,
                ] : null,
                'divisi' => $this->user->divisi
                ? [
                    'id' => $this->user->divisi->id,
                    'kodedivisi' => $this->user->divisi->kodedivisi,
                    'divisi' => $this->user->divisi->divisi,
                    'short' => $this->user->divisi->short,
                    'status' => $this->user->divisi->status,
                ] : null,
            ],
        ];
    }
}
