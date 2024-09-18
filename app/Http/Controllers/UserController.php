<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\ImageService;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected UserService $userService;
    protected ImageService $imageService;

    public function __construct(UserService $userService, ImageService $imageService)
    {
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $this->getPaginationAndFilterParams($request);

        $categories = $this->userService->getUsers(
            $params['pageSize'],
            $params['sortField'],
            $params['sortOrder'],
            $params['filterField'],
            $params['filterValue'],
            $params['filterOperator']
        );

        return response()->json(new UserCollection($categories));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ApiResponse::success(['user' => new UserResource($user)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return $this->userService->deleteUser($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->userService->updateUser($request, $user);

        return ApiResponse::success(["user" => new UserResource($updatedUser)]);
    }

    public function editUserProfile(EditUserProfileRequest $request, User $user)
    {
        $this->userService->editProfile($request, $user);

        return ApiResponse::success(['message' => "User profile edited successfully."]);
    }

    public function uploadAvatar(UploadImageRequest $request)
    {
        $avatarUrl = $this->imageService->uploadAvatar($request);

        return ApiResponse::success([
            'avatarUrl' => $avatarUrl
        ]);
    }
}
