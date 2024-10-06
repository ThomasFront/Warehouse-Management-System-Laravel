<?php

namespace App\Http\Services;

use App\Http\Requests\CreateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\App;

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

        $sales = Sale::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_price')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get();

        return $sales;
    }

    public function exportToCsvFormat(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');
        App::setLocale($locale);

        $data = Sale::orderBy('updated_at', 'desc')->get();

        $csvHeader = [
            __('csv.ID'),
            __('csv.Product'),
            __('csv.Quantity'),
            __('csv.Sales price'),
            __('csv.Total price'),
            __('csv.Created at')
        ];

        $csvContent = implode(',', $csvHeader) . "\n";

        foreach ($data as $row){
            $csvContent = $csvContent . "{$row->id},{$row->product->name},{$row->quantity},{$row->product->price},{$row->total_price},{$row->created_at}\n";
        }

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales_export.csv"',
        ]);
    }

    public function getTopProducts()
    {
        $currentYear = Carbon::now()->year;

        $topProducts = Sale::select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->whereYear('created_at', $currentYear)
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();

        return $topProducts;
    }
}
