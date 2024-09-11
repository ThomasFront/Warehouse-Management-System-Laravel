<?php

namespace App\Http\Services;

class FilterService
{
    public function applyFilters($query, $filterField, $filterOperator, $filterValue)
    {
        if ($filterField && $filterOperator && $filterValue) {
            switch ($filterOperator) {
                case 'contains':
                    $query->where($filterField, 'like', "%$filterValue%");
                    break;
                case 'equals':
                    $query->where($filterField, $filterValue);
                    break;
                case 'startsWith':
                    $query->where($filterField, 'like', "$filterValue%");
                    break;
                case 'endsWith':
                    $query->where($filterField, 'like', "%$filterValue");
                    break;
                case 'isEmpty':
                    $query->whereNull($filterField);
                    break;
                case 'isNotEmpty':
                    $query->whereNotNull($filterField);
                    break;
                case 'isAnyOf':
                    if (is_array($filterValue)) {
                        $query->whereIn($filterField, $filterValue);
                    }
                    break;
                default:
                    break;
            }
        }
    }
}
