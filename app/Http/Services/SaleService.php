<?php

namespace App\Http\Services;

use App\Http\Requests\CreateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SaleService
{
    protected FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

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

    public function getSales($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator)
    {
        $sortField = Str::snake($sortField);
        $filterField = Str::snake($filterField);

        $query = Sale::orderBy('updated_at', 'desc')
            ->orderBy($sortField, $sortOrder);

        $this->filterService->applyFilters($query, $filterField, $filterOperator, $filterValue);

        return $query->paginate($pageSize);
    }

    public function getTotalPrice()
    {
        return Sale::sum('total_price');
    }

    public function getMonthlySales()
    {
        $currentYear = Carbon::now()->year;

        $monthlySales = array_fill(0, 12, 0);

        $sales = Sale::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_price')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get();

        foreach ($sales as $sale){
            $monthlySales[$sale->month - 1] = (float) $sale->total_price;
        }

        return $monthlySales;
    }
}
