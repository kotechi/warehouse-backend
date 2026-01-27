<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenanggungJawabAsetResource extends JsonResource
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
            'user_id' => $this->user_id,
            'unit_eselon_ii_id' => $this->unit_eselon_ii_id,
            'nama_pic' => $this->nama_pic,
            'nip' => $this->nip,
            'jabatan' => $this->jabatan,
            'telepon' => $this->telepon,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'unit_eselon_ii' => new UnitEselonIiResource($this->whenLoaded('unitEselonIi')),
            'asets' => AsetResource::collection($this->whenLoaded('asets')),
            'asets_count' => $this->when($this->relationLoaded('asets'), function () {
                return $this->asets->count();
            }),
        ];
    }
}
