<?php

namespace Tests\Unit;

use App\Http\Resources\MonthlySalesCollection;
use App\Http\Resources\TopProductsCollection;
use App\Http\Services\CategoryService;
use App\Http\Services\DashboardService;
use App\Http\Services\ProductService;
use App\Http\Services\SaleService;
use App\Http\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DashboardService $dashboardService;
    protected UserService $userService;
    protected ProductService $productService;
    protected CategoryService $categoryService;
    protected SaleService $saleService;


    public function setUp(): void
    {
        parent::setUp();

        $this->userService = Mockery::mock(UserService::class);
        $this->productService = Mockery::mock(ProductService::class);
        $this->categoryService = Mockery::mock(CategoryService::class);
        $this->saleService = Mockery::mock(SaleService::class);

        $this->dashboardService = new DashboardService(
            $this->userService,
            $this->productService,
            $this->categoryService,
            $this->saleService
        );
    }

    public function test_get_dashboard_data()
    {
        $this->userService->shouldReceive('countUsers')
            ->once()
            ->andReturn(100);

        $this->productService->shouldReceive('countProducts')
            ->once()
            ->andReturn(50);

        $this->categoryService->shouldReceive('countCategories')
            ->once()
            ->andReturn(10);

        $this->saleService->shouldReceive('getTotalPrice')
            ->once()
            ->andReturn(5000);

        $monthlySales = [
            [
                'month' => 1,
                'total_price' => 1000
            ],
            [
                'month' => 2,
                'total_price' => 2000
            ]
        ];

        $this->saleService->shouldReceive('getMonthlySales')
            ->once()
            ->andReturn($monthlySales);

        $topProducts = [
            [
                'product_id' => 1,
                'total_quantity' => 100
            ],
            [
                'product_id' => 2,
                'total_quantity' => 80
            ]
        ];

        $this->saleService->shouldReceive('getTopProducts')
            ->once()
            ->andReturn($topProducts);

        $dashboardData = $this->dashboardService->getDashboardData();

        $this->assertEquals(100, $dashboardData['userCount']);
        $this->assertEquals(50, $dashboardData['productCount']);
        $this->assertEquals(10, $dashboardData['categoryCount']);
        $this->assertEquals(5000, $dashboardData['totalPrice']);

        $this->assertInstanceOf(MonthlySalesCollection::class, $dashboardData['monthlySales']);
        $this->assertCount(2, $dashboardData['monthlySales']->collection);
        $this->assertEquals(1, $dashboardData['monthlySales']->collection[0]['month']);
        $this->assertEquals(1000, $dashboardData['monthlySales']->collection[0]['total_price']);
        $this->assertEquals(2, $dashboardData['monthlySales']->collection[1]['month']);
        $this->assertEquals(2000, $dashboardData['monthlySales']->collection[1]['total_price']);

        $this->assertInstanceOf(TopProductsCollection::class, $dashboardData['topProducts']);
        $this->assertEquals($topProducts, $dashboardData['topProducts']->collection->toArray());
    }
}
