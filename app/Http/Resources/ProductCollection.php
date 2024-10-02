<?php

namespace App\Http\Resources;

use App\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    use HasPagination;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function($product) {
                return new ProductResource($product);
            }),
            'meta' => $this->paginationData($this->resource),
        ];
    }
}
