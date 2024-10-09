<?php

namespace Tests\Unit;

use App\Http\Requests\CreateSaleRequest;
use App\Http\Services\FilterService;
use App\Http\Services\SaleService;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Mockery;

class SaleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SaleService $saleService;

    protected FilterService $filterService;

    public function setUp(): void
    {
        parent::setUp();

        $this->filterService = Mockery::mock(FilterService::class);
        $this->saleService = new SaleService($this->filterService);
    }

    public function test_get_sales()
    {
        Product::factory()->create();
        Sale::factory()->count(5)->create();

        $this->filterService
            ->shouldReceive('applyFilters')
            ->once();

        $pageSize = 2;
        $sortField = 'quantity';
        $sortOrder = 'asc';
        $filterField = 'quantity';
        $filterValue = 2;
        $filterOperator = 'equals';

        $result = $this->saleService->getSales($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount($pageSize, $result);
        $this->assertEquals(5, $result->total());
    }

    public function test_sell_success()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Sample name',
            'description' => 'Sample description.',
            'category_id' => $category->id,
            'stock' => 10,
            'price' => 100
        ]);

        $requestData = [
            'product_id' => $product->id,
            'quantity' => 5,
        ];

        $request = Mockery::mock(CreateSaleRequest::class);
        $request->shouldReceive('validated')->andReturn($requestData);

        $response = $this->saleService->sell($request);

        $this->assertEquals(201, $response->getStatusCode());

        $product->refresh();
        $this->assertEquals(5, $product->stock);

        $this->assertDatabaseHas('sales', [
            'product_id' => $product->id,
            'quantity' => 5,
            'total_price' => $requestData['quantity'] * $product->price
        ]);
    }

    public function test_sell_insufficient_stock()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Sample name',
            'description' => 'Sample description.',
            'category_id' => $category->id,
            'stock' => 3,
            'price' => 100
        ]);

        $requestData = [
            'product_id' => $product->id,
            'quantity' => 5
        ];

        $request = Mockery::mock(CreateSaleRequest::class);
        $request->shouldReceive('validated')->andReturn($requestData);

        $response = $this->saleService->sell($request);

        $this->assertEquals(400, $response->getStatusCode());

        $product->refresh();
        $this->assertEquals(3, $product->stock);

        $this->assertEquals(['message' => 'Insufficient stock'], $response->getData(true)['error']);
    }


    public function test_get_total_price()
    {
        Product::factory()->create();
        $sales = Sale::factory()->count(2)->create();

        $salesSum = $sales->sum('total_price');

        $totalPrice = $this->saleService->getTotalPrice();

        $this->assertEquals(round($salesSum, 2), round($totalPrice, 2));
    }

    public function test_get_top_products()
    {
        Product::factory()->create();
        $currentYear = Carbon::now()->year;

        $product1 = Product::factory()->create(['price' => 100]);
        $product2 = Product::factory()->create(['price' => 150]);
        $product3 = Product::factory()->create(['price' => 200]);

        Sale::factory()->create([
            'product_id' => $product1->id,
            'quantity' => 5,
            'total_price' => 5 * $product1['price'],
            'created_at' => Carbon::create($currentYear, 1, 15),
        ]);
        Sale::factory()->create([
            'product_id' => $product2->id,
            'quantity' => 500,
            'total_price' => 3 * $product2['price'],
            'created_at' => Carbon::create($currentYear, 2, 10),
        ]);
        Sale::factory()->create([
            'product_id' => $product3->id,
            'quantity' => 100,
            'total_price' => 7 * $product3['price'],
            'created_at' => Carbon::create($currentYear, 3, 20),
        ]);

        $topProducts = $this->saleService->getTopProducts();

        $this->assertCount(3, $topProducts);
        $this->assertEquals($product1->id, $topProducts[2]->product_id);
        $this->assertEquals($product2->id, $topProducts[0]->product_id);
        $this->assertEquals($product3->id, $topProducts[1]->product_id);

        $this->assertEquals(500, $topProducts[0]->total_quantity);
        $this->assertEquals(100, $topProducts[1]->total_quantity);
        $this->assertEquals(5, $topProducts[2]->total_quantity);
    }

    public function test_export_to_csv_format_with_en_header()
    {
        $product = Product::factory()->create();
        Sale::factory()->count(3)->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'total_price' => 5 * $product->price,
            'created_at' => Carbon::create(now()->year, 1, 10, 2, 4)
        ]);

        $request = new Request();

        $response = $this->saleService->exportToCsvFormat($request);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="sales_export.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsvHeader = "ID,Product,Quantity,Sales price,Total price,Created at\n";
        $expectedCsvContent = $expectedCsvHeader;

        $sales = Sale::orderBy('updated_at', 'desc')->get();

        foreach ($sales as $sale) {
            $expectedCsvContent .= "{$sale->id},{$sale->product->name},{$sale->quantity},{$sale->product->price},{$sale->total_price},{$sale->created_at}\n";
        }

        $this->assertEquals($expectedCsvContent, $response->getContent());
    }

    public function test_export_to_csv_format_with_pl_header()
    {
        $product = Product::factory()->create();
        Sale::factory()->count(3)->create([
            'product_id' => $product->id,
            'quantity' => 5,
            'total_price' => 5 * $product->price,
            'created_at' => Carbon::create(now()->year, 1, 10, 2, 4)
        ]);

        $request = new Request();
        $request->headers->set('Accept-Language', 'pl');

        $response = $this->saleService->exportToCsvFormat($request);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('text/csv', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="sales_export.csv"', $response->headers->get('Content-Disposition'));

        $expectedCsvHeader = "ID,Produkt,Ilość sztuk,Cena sprzedaży,Cena całkowita,Data utworzenia\n";
        $expectedCsvContent = $expectedCsvHeader;

        $sales = Sale::orderBy('updated_at', 'desc')->get();

        foreach ($sales as $sale) {
            $expectedCsvContent .= "{$sale->id},{$sale->product->name},{$sale->quantity},{$sale->product->price},{$sale->total_price},{$sale->created_at}\n";
        }

        $this->assertEquals($expectedCsvContent, $response->getContent());
    }
}
