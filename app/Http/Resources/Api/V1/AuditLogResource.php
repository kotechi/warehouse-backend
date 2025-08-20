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
            'user' => [
                'userId' => $this->user->id,
                'userName' => $this->user->name
            ],
            'barang' => [
                'idBarang' => $this->barang->id,
                'namaBarang' => $this->barang->produk
            ],
            'type' => $this->type,
        ];
    }
}
