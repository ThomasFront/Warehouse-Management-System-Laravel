<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ImageService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
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
    public function store(Request $request)
    {

    }

    public function uploadProductImage(UploadImageRequest $request)
    {
        $productImageUrl = $this->imageService->uploadProductImage($request);

        return ApiResponse::success([
            'image' => $productImageUrl
        ]);
    }
}
