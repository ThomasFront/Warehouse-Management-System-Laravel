<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ImageService;
use App\Http\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected ImageService $imageService;
    protected ProductService $productService;

    public function __construct(ImageService $imageService, ProductService $productService)
    {
        $this->imageService = $imageService;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $this->getPaginationAndFilterParams($request);

        $products = $this->productService->getProducts(
            $params['pageSize'],
            $params['sortField'],
            $params['sortOrder'],
            $params['filterField'],
            $params['filterValue'],
            $params['filterOperator']
        );

        return response()->json(new ProductCollection($products));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ApiResponse::success(['product' => new ProductResource($product)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->addProduct($request);

        return ApiResponse::success(["product" => new ProductResource($product)], 201);
    }

    public function uploadProductImage(UploadImageRequest $request)
    {
        $productImageUrl = $this->imageService->uploadProductImage($request);

        return ApiResponse::success([
            'image' => $productImageUrl
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return ApiResponse::success(['message' => 'Product deleted successfully.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $updatedProduct = $this->productService->updateProduct($request, $product);

        return ApiResponse::success(["user" => new ProductResource($updatedProduct)]);
    }

    public function dropdownProvider()
    {
        $dropdownProvider = $this->getDropdownProvider(new Product);
        return ApiResponse::success(['dropdown' => $dropdownProvider]);
    }

    public function exportCsv()
    {
        return $this->productService->exportToCsvFormat();
    }
}
