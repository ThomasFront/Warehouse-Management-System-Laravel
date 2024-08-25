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
            'perPage' => $paginator->perPage(),
            'currentPage' => $paginator->currentPage(),
            'lastPage' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
