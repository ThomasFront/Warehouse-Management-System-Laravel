<?php

namespace App\Http\Services;

use App\Http\Requests\UploadImageRequest;

class ImageService
{
    public function uploadAvatar(UploadImageRequest $request): string
    {
        return $request->file('image')->store('public/avatars');
    }

    public function uploadProductImage(UploadImageRequest $request): string
    {
        return $request->file('image')->store('public/products');
    }
}
