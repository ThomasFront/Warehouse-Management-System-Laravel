<?php

namespace App\Http\Services;

use App\Http\Requests\CreateProductRequest;
use App\Models\Product;
use Illuminate\Support\Str;

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
}
