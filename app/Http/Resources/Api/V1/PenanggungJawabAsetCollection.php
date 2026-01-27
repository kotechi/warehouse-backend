<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PenanggungJawabAsetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->total()),
                'per_page' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->perPage()),
                'current_page' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->currentPage()),
                'last_page' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->lastPage()),
                'from' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->firstItem()),
                'to' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->lastItem()),
            ],
            'links' => [
                'first' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->url(1)),
                'last' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->url($this->lastPage())),
                'prev' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->previousPageUrl()),
                'next' => $this->when($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator, $this->nextPageUrl()),
            ],
        ];
    }
}
