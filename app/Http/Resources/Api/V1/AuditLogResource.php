<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'deskripsi' => $this->deskripsi,
            'newValues' => $this->new_values,
            'oldValues' => $this->old_values,
            'inputValus' => $this->input_values,
            'inputValues' => $this->new_values-$this->old_values,
            'user' => [
                'userId'     => $this->user->id,
                'userName'   => $this->user->name,
                'divisiId'   => $this->user->divisi_id,
                'divisiName' => $this->user->divisi?->nama,
                'jabatanId'  => $this->user->jabatan_id,
                'jabatanName'=> $this->user->jabatan?->nama,
            ],
            'barang' => $this->barang ? [
                'idBarang'   => $this->barang->id,
                'namaBarang' => $this->barang->produk,
            ] : null,
            'type' => $this->type,
        ];
    }

}
