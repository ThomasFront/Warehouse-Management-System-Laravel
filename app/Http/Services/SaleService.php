<?php

namespace App\Http\Services;

use App\Http\Requests\CreateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\Sale;

class SaleService
{
    public function sell(CreateSaleRequest $request)
    {
        $validatedData = $request->validated();

        $product = Product::findOrFail($validatedData['product_id']);

        if($product->stock < $validatedData['quantity']){
            return ApiResponse::error(['message' => 'Insufficient stock']);
        }

        $totalPrice = $validatedData['quantity'] * $product->price;

        $product->stock -= $validatedData['quantity'];
        $product->save();

        $sale = Sale::create([
            'product_id' => $product->id,
            'quantity' => $validatedData['quantity'],
            'total_price' => $totalPrice
        ]);

        return ApiResponse::success(['sale' => new SaleResource($sale)], 201);
    }
}
