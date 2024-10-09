<?php

namespace Tests\Unit;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Services\FilterService;
use App\Http\Services\ProductService;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProductService $productService;

    protected FilterService $filterService;

    public function setUp(): void
    {
        parent::setUp();

        $this->filterService = Mockery::mock(FilterService::class);
        $this->productService = new ProductService($this->filterService);
    }

    public function test_get_products()
    {
        Product::factory()->count(5)->create();

        $this->filterService
            ->shouldReceive('applyFilters')
            ->once();

        $pageSize = 2;
        $sortField = 'name';
        $sortOrder = 'asc';
        $filterField = 'name';
        $filterValue = 'name';
        $filterOperator = 'equals';

        $result = $this->productService->getProducts($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount($pageSize, $result);
        $this->assertEquals(5, $result->total());
    }


    public function test_add_product()
    {
        $category = Category::factory()->create();

        $productData = [
            'image' => 'path/to/image.jpg',
            'name' => 'Test Product',
            'category_id' => $category->id,
            'price' => 99.99,
            'stock' => 50,
            'description' => 'This is a test product description.'
        ];

        $request = Mockery::mock(CreateProductRequest::class);
        $request->shouldReceive('validated')
            ->andReturn($productData);

        $product = $this->productService->addProduct($request);

        $this->assertDatabaseHas('products', $productData);
        $this->assertTrue($product->relationLoaded('category'));
        $this->assertEquals($productData['name'], $product->name);
        $this->assertEquals($productData['price'], $product->price);
    }

    public function test_update_product()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $updatedData = [
            'image' => 'path/to/new/image.png',
            'name' => 'Updated Product Name',
            'category_id' => $category->id,
            'price' => 75.00,
            'stock' => 20,
            'description' => 'Updated description that is more than 10 characters.'
        ];

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($updatedData);

        $updatedProduct = $this->productService->updateProduct($request, $product);

        $this->assertDatabaseHas('products', $updatedData);

        $this->assertEquals($updatedData['name'], $updatedProduct->name);
        $this->assertEquals($updatedData['price'], $updatedProduct->price);
        $this->assertEquals($updatedData['stock'], $updatedProduct->stock);
        $this->assertEquals($updatedData['description'], $updatedProduct->description);
        $this->assertEquals($updatedData['image'], $updatedProduct->image);
        $this->assertEquals($updatedData['category_id'], $updatedProduct->category_id);
    }

    public function test_count_products()
    {
        Product::factory()->count(10)->create();

        $count = $this->productService->countProducts();

        $this->assertEquals(10, $count);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', ['id' => $product->id]);

        $this->productService->deleteProduct($product);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_export_to_csv_format_with_en_header()
    {
        $category = Category::factory()->create(['name' => 'Sample Category']);
        Product::factory()->count(3)->create([
            'name' => 'Sample name',
            'category_id' => $category->id,
            'price' => 10.00,
            'stock' => 100,
            'description' => 'Sample description.'
        ]);

        $request = new Request();

        $response = $this->productService->exportToCsvFormat($request);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="products_export.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsvHeader = "ID,Name,Category,Price,Stock,Description\n";
        $expectedCsvContent = $expectedCsvHeader;

        $products = Product::orderBy('updated_at', 'desc')->get();

        foreach ($products as $product) {
            $expectedCsvContent .= "{$product->id},{$product->name},{$product->category->name},{$product->price},{$product->stock},{$product->description}\n";
        }

        $this->assertEquals($expectedCsvContent, $response->getContent());
    }

    public function test_export_to_csv_format_with_pl_header()
    {
        $category = Category::factory()->create(['name' => 'Sample Category']);
        Product::factory()->count(3)->create([
            'name' => 'Sample name',
            'category_id' => $category->id,
            'price' => 10.00,
            'stock' => 100,
            'description' => 'Sample description.'
        ]);

        $request = new Request();
        $request->headers->set('Accept-Language', 'pl');

        $response = $this->productService->exportToCsvFormat($request);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="products_export.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsvHeader = "ID,Nazwa,Kategoria,Cena/sztuka,Ilość na stanie,Opis\n";
        $expectedCsvContent = $expectedCsvHeader;

        $products = Product::orderBy('updated_at', 'desc')->get();

        foreach ($products as $product) {
            $expectedCsvContent .= "{$product->id},{$product->name},{$product->category->name},{$product->price},{$product->stock},{$product->description}\n";
        }

        $this->assertEquals($expectedCsvContent, $response->getContent());
    }
}
