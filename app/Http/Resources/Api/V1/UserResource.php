<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'jabatan' => $this->jabatan ? [
                'id' => $this->jabatan->id,
                'name' => $this->jabatan->jabatan,
            ] : null,
            'divisi' => $this->divisi ? [
                'id' => $this->divisi->id,
                'kodedivisi' => $this->divisi->kodedivisi,
                'divisi' => $this->divisi->divisi,
                'short' => $this->divisi->short,
                'status' => $this->divisi->status,
            ] : null,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
