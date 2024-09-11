<?php

namespace App\Http\Services;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    protected FilterService $filterService;
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }
    public function getCategories($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator)
    {
        $query = Category::orderBy('updated_at', 'desc')
            ->orderBy($sortField, $sortOrder);

        $this->filterService->applyFilters($query, $filterField, $filterOperator, $filterValue);

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

    public function deleteCategory(Category $category)
    {
        $category->delete();
    }
}
