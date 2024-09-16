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
        $user = $this->authService->me();

        if($user->avatar){
            Storage::delete($user->avatar);
        }

        $avatarPath = $request->file('avatar')->store('public/avatars');
        $user->avatar = $avatarPath;
        $user->save();

        return Storage::url($avatarPath);
    }
}
