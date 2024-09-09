<?php

namespace App\Http\Services;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    public function getCategories($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator)
    {
        $query = Category::orderBy('updated_at', 'desc')
            ->orderBy($sortField, $sortOrder);

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

        return $query->paginate($pageSize);
    }

    public function storeCategory(StoreCategoryRequest $data)
    {
        return Category::create($data->all());
    }

    public function updateCategory(UpdateCategoryRequest $request, Category $category): Category
    {
        $category->update($request->validated());

        return $category;
    }
}
