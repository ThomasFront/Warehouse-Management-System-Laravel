<?php

namespace App\Http\Services;

use App\Http\Requests\CreateProductRequest;
use App\Models\Product;

class ProductService
{
    public function addProduct(CreateProductRequest $request)
    {
        $validatedData = $request->validated();

        $product = Product::create($validatedData);

        $product->load('category');

        return $product;
    }
}
