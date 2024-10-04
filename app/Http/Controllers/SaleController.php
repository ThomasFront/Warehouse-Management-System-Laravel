<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSaleRequest;
use App\Http\Services\SaleService;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    protected SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSaleRequest $request)
    {
        return $this->saleService->sell($request);
    }
}
