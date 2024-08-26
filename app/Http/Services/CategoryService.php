<?php

namespace App\Http\Services;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    public function getCategories()
    {
        $perPage = config('pagination.per_page');

        return Category
            ::orderBy('updated_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
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
