<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $this->getPaginationAndFilterParams($request);

        $categories = $this->categoryService->getCategories(
            $params['pageSize'],
            $params['sortField'],
            $params['sortOrder'],
            $params['filterField'],
            $params['filterValue'],
            $params['filterOperator']
        );

        return response()->json(new CategoryCollection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $createdCategory = $this->categoryService->storeCategory($request);

        return ApiResponse::success(["category" => new CategoryResource($createdCategory)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return ApiResponse::success(['category' => new CategoryResource($category)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryService->updateCategory($request, $category);

        return ApiResponse::success(["category" => new CategoryResource($updatedCategory)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return $this->categoryService->deleteCategory($category);
    }

    public function dropdownProvider()
    {
        $dropdownProvider = $this->getDropdownProvider(new Category);
        return ApiResponse::success(['dropdown' => $dropdownProvider]);
    }
}
