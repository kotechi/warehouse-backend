<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
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
            'kategori' => [
                'id' => $this->kategori->id,
                'kategori' => $this->kategori->kategori,
                'status' => $this->kategori->status,
                'createdAt' => $this->kategori->created_at,
                'updatedAt' => $this->kategori->updated_at,
            ],
            'divisi' => [
                'id' => $this->divisi->id,
                'kodedivisi' => $this->divisi->kodedivisi,
                'divisi' => $this->divisi->divisi,
                'short' => $this->divisi->short,
                'status' => $this->divisi->status,
            ],
            'createdBy' => [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email,
            ],
            'updatedBy' => $this->updatedBy
            ? [
                'id' => $this->updatedBy->id,
                'name' => $this->updatedBy->name,
                'email' => $this->updatedBy->email,
            ]
            : null,
            'namaBarang' => $this->produk,
            'kodeQr' => $this->kode_qr,
            'lineDivisi' => $this->line_divisi,
            'productionDate' => $this->production_date,
            'stockAwal' => $this->stock_awal,
            'stockSekarang' => $this->stock_sekarang,
        ];
    }
}
