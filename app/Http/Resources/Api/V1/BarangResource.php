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
            'stockAwal' => $this->stock_awal,
            'totalStock' => $this->stock_sekarang,
            'daftarStock' => $this->stock->map(function($stockItem) {
                return [
                    'id'             => $stockItem->id,
                    'barangId'       => $stockItem->barang_id,
                    'userId'         => $stockItem->user_id,
                    'stock'          => $stockItem->stock,
                    'kodeQr'         => $stockItem->kode_qr,
                    'keterangan'     => $stockItem->keterangan,
                    'productionDate' => $stockItem->production_date,
                    'type'           => $stockItem->type,
                    'createdAt'      => $stockItem->created_at,
                    'updatedAt'      => $stockItem->updated_at
                ];
            }),
            'createdBy' => [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
                'email' => $this->createdBy->email,
                'jabatan' => $this->createdBy->jabatan
                ? [
                    'id' => $this->createdBy->jabatan->id,
                    'name' => $this->createdBy->jabatan->jabatan,
                ] : null,
                'divisi' => $this->createdBy->divisi
                ? [
                    'id' => $this->createdBy->divisi->id,
                    'kodedivisi' => $this->createdBy->divisi->kodedivisi,
                    'divisi' => $this->createdBy->divisi->divisi,
                    'short' => $this->createdBy->divisi->short,
                    'status' => $this->createdBy->divisi->status,
                ] : null,
            ],
            'updatedBy' => $this->updatedBy
            ? [
                'id' => $this->updatedBy->id,
                'name' => $this->updatedBy->name,
                'email' => $this->updatedBy->email,
                'jabatan' => $this->updatedBy->jabatan
                ? [
                    'id' => $this->updatedBy->jabatan->id,
                    'name' => $this->updatedBy->jabatan->jabatan,
                ] : null,
                'divisi' => $this->updatedBy->divisi
                ? [
                    'id' => $this->updatedBy->divisi->id,
                    'kodedivisi' => $this->updatedBy->divisi->kodedivisi,
                    'divisi' => $this->updatedBy->divisi->divisi,
                    'short' => $this->updatedBy->divisi->short,
                    'status' => $this->updatedBy->divisi->status,
                ] : null,
            ]
            : null,
            'status' => $this->status,
            'namaBarang' => $this->produk,
            'kodeQr' => $this->kode_qr,
            'kodeGrp' => $this->kodegrp,
            'lineDivisi' => $this->divisi
            ? [
                'id' => $this->divisi->id,
                'kodedivisi' => $this->divisi->kodedivisi,
                'divisi' => $this->divisi->divisi,
                'short' => $this->divisi->short,
                'status' => $this->divisi->status,
            ] : null,
            'productionDate' => $this->production_date,
        ];
    }
}
