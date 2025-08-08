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
            'namaBarang' => $this->nama_barang,
            'kodeQr' => $this->kode_qr,
            'lineDivisi' => $this->line_divisi,
            'productionDate' => $this->production_date,
            'stockAwal' => $this->stock_awal,
            'stockSekarang' => $this->stock_sekarang,
        ];
    }
}
