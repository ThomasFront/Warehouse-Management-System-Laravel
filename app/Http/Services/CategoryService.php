<?php

namespace App\Http\Services;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryService
{
    public function getCategories()
    {
        return Category::paginate(10);
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
