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

class CategoryController extends Controller
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
        $pageSize = $request->input('pageSize', 25);
        $sortField = $request->input('sortField', 'created_at');
        $sortOrder = $request->input('sortOrder', 'asc');

        $filterField = $request->input('filterField');
        $filterValue = $request->input('filterValue');
        $filterOperator = $request->input('filterOperator');

        $categories = $this->categoryService->getCategories($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator);

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
        return response()->json(new CategoryResource($category));
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
        $category->delete();

        return ApiResponse::success(['message' => 'Category deleted successfully.']);
    }
}
