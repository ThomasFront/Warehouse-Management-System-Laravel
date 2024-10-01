<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ImageService;
use App\Http\Services\ProductService;

class ProductController extends Controller
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
    public function index()
    {
        //
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
}
