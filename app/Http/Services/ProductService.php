<?php

namespace App\Http\Services;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class ProductService
{
    protected FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }
    public function addProduct(CreateProductRequest $request)
    {
        $validatedData = $request->validated();

        $product = Product::create($validatedData);

        $product->load('category');

        return $product;
    }

    public function getProducts($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator)
    {
        $sortField = Str::snake($sortField);
        $filterField = Str::snake($filterField);

        $query = Product::orderBy('updated_at', 'desc')
            ->orderBy($sortField, $sortOrder);

        $this->filterService->applyFilters($query, $filterField, $filterOperator, $filterValue);

        return $query->paginate($pageSize);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }

    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $product;
    }

    public function countProducts()
    {
        return Product::count();
    }

    public function exportToCsvFormat(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        App::setLocale($locale);

        $data = Product::orderBy('updated_at', 'desc')->get();

        $csvHeader = [
            __('csv.ID'),
            __('csv.Name'),
            __('csv.Category'),
            __('csv.Price'),
            __('csv.Stock'),
            __('csv.Description')
        ];

        $csvContent = implode(',', $csvHeader) . "\n";

        foreach ($data as $row){
            $csvContent = $csvContent . "{$row->id},{$row->name},{$row->category->name},{$row->price},{$row->stock},{$row->description}\n";
        }

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_export.csv"',
        ]);
    }
}
