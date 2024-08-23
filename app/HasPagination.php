<?php

namespace App;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasPagination
{
    /**
     * Get the pagination information for a LengthAwarePaginator instance.
     *
     * @param LengthAwarePaginator $paginator
     * @return array<string, mixed>
     */
    protected function paginationData(LengthAwarePaginator $paginator): array
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
