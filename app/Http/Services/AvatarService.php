<?php

namespace App\Http\Services;

use App\Http\Requests\UploadAvatarRequest;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function uploadAvatar(UploadAvatarRequest $request): string
    {
        return $request->file('avatar')->store('public/avatars');
    }
}
