<?php

namespace Tests\Unit;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Services\CategoryService;
use App\Http\Services\FilterService;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryService $categoryService;

    protected FilterService $filterService;

    private const CATEGORY_COUNT = 10;

    public function setUp(): void
    {
        parent::setUp();

        $this->filterService = Mockery::mock(FilterService::class);
        $this->categoryService = new CategoryService($this->filterService);
    }

    public function test_get_categories()
    {
        Category::factory()->count(5)->create();

        $this->filterService
            ->shouldReceive('applyFilters')
            ->once();

        $pageSize = 2;
        $sortField = 'name';
        $sortOrder = 'asc';
        $filterField = 'name';
        $filterValue = 'name';
        $filterOperator = 'equals';

        $result = $this->categoryService->getCategories($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount($pageSize, $result);
        $this->assertEquals(5, $result->total());
    }

    public function test_add_category()
    {
        $categoryData = [
            'name' => 'New category'
        ];

        $request = Mockery::mock(StoreCategoryRequest::class);
        $request->shouldReceive('all')->andReturn($categoryData);

        $category = $this->categoryService->storeCategory($request);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($categoryData['name'], $category->name);
        $this->assertDatabaseHas('categories', [
            'name' => $categoryData['name'],
        ]);
    }

    public function test_count_categories()
    {
        Category::factory()->count(self::CATEGORY_COUNT)->create();

        $countCategories = $this->categoryService->countCategories();

        $this->assertEquals(self::CATEGORY_COUNT, $countCategories);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create();

        $updatedData = [
            'name' => 'Updated Category Name',
        ];

        $request = Mockery::mock(UpdateCategoryRequest::class);
        $request->shouldReceive('validated')->andReturn($updatedData);

        $updatedCategory = $this->categoryService->updateCategory($request, $category);

        $this->assertEquals($updatedData['name'], $updatedCategory->name);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $updatedData['name'],
        ]);
    }

    public function test_delete_category_success()
    {
        $category = Category::factory()->create();

        $response = $this->categoryService->deleteCategory($category);

        $this->assertEquals('Category successfully deleted', $response->getData()->data->message);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_delete_category_with_products_failure()
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->categoryService->deleteCategory($category);

        $this->assertEquals(
            'You cannot delete a category that is assigned to a product',
            $response->getData()->message
        );
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
