<?php

namespace App\Http\Services;

class DashboardService
{
    protected UserService $userService;
    protected ProductService $productService;
    protected CategoryService $categoryService;

    public function __construct(
        UserService $userService,
        ProductService $productService,
        CategoryService $categoryService
    )
    {
        $this->userService = $userService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function getDashboardData()
    {
        $userCount = $this->userService->countUsers();
        $productCount = $this->productService->countProducts();
        $categoryCount = $this->categoryService->countCategories();

        return [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'categoryCount' => $categoryCount
        ];
    }
}
