<?php

namespace App\Http\Resources;

use App\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            'data' => $this->collection->map(function($user) {
                return new UserResource($user);
            }),
            'meta' => $this->paginationData($this->resource),
        ];
    }
}
