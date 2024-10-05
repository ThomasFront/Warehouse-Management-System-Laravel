<?php

namespace App\Http\Services;

class DashboardService
{
    protected UserService $userService;
    protected ProductService $productService;
    protected CategoryService $categoryService;

    protected SaleService $saleService;

    public function __construct(
        UserService $userService,
        ProductService $productService,
        CategoryService $categoryService,
        SaleService $saleService
    )
    {
        $this->userService = $userService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->saleService = $saleService;
    }
    public function getDashboardData()
    {
        $userCount = $this->userService->countUsers();
        $productCount = $this->productService->countProducts();
        $categoryCount = $this->categoryService->countCategories();
        $totalPrice = $this->saleService->getTotalPrice();

        return [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'totalPrice' => $totalPrice
        ];
    }
}
