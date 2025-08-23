<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'id' => $this->id,
            'produk' => $this->produk,
            'kodegrp' => $this->kodegrp,
            'stockSekarang' => $this->stock_sekarang,
            'kategori' => [
                'id' => $this->kategori->id,
                'kategori' => $this->kategori->kategori,
                'status' => $this->kategori->status,
                'createdAt' => $this->kategori->created_at,
                'updatedAt' => $this->kategori->updated_at,
            ],
            'divisi' => [
                'id' => $this->divisi->id,
                'kodeDivisi' => $this->divisi->kodedivisi,
                'divisi' => $this->divisi->divisi,
                'short' => $this->divisi->short,
                'status' => $this->divisi->status,
            ],
        ];
    }
}
