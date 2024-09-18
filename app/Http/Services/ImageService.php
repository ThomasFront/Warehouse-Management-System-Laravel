<?php

namespace App\Http\Services;

use App\Http\Requests\UploadImageRequest;

class ImageService
{
    public function uploadAvatar(UploadImageRequest $request): string
    {
        return $request->file('avatar')->store('public/avatars');
    }
}
