<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AsetResource extends JsonResource
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
            'kode_aset' => $this->kode_aset,
            'nama_aset' => $this->nama_aset,
            'detail_kategori_aset_id' => $this->detail_kategori_aset_id,
            'penanggung_jawab_aset_id' => $this->penanggung_jawab_aset_id,
            'status' => $this->status,
            'kondisi_fisik' => $this->kondisi_fisik,
            'harga_perolehan' => $this->harga_perolehan,
            'mata_uang' => $this->mata_uang,
            'tanggal_perolehan' => $this->tanggal_perolehan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships (load when available)
            'detail_kategori_aset' => $this->whenLoaded('detailKategoriAset'),
            'penanggung_jawab_aset' => new PenanggungJawabAsetResource($this->whenLoaded('penanggungJawabAset')),
        ];
    }
}
