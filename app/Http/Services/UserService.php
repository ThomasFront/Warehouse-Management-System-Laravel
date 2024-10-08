<?php

namespace App\Http\Services;

use App\Http\Requests\EditUserProfileRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Responses\ApiResponse;

class UserService
{
    protected FilterService $filterService;
    protected AuthService $authService;

    public function __construct(FilterService $filterService, AuthService $authService)
    {
        $this->filterService = $filterService;
        $this->authService = $authService;
    }

    public function getUsers($pageSize, $sortField, $sortOrder, $filterField, $filterValue, $filterOperator)
    {
        $sortField = Str::snake($sortField);
        $filterField = Str::snake($filterField);

        $query = User::orderBy('updated_at', 'desc')
            ->orderBy($sortField, $sortOrder);

        $this->filterService->applyFilters($query, $filterField, $filterOperator, $filterValue);

        return $query->paginate($pageSize);
    }

    public function deleteUser(User $user)
    {
        $isCurrentUser = $this->authService->me()->id === $user->id;

        if ($isCurrentUser) {
            return ApiResponse::error(['message' => 'You cannot delete your own account'], 403);
        }

        $user->delete();
        return ApiResponse::success(['message' => 'User deleted successfully.']);
    }

    public function updateUser(UpdateUserRequest $request, User $user): User
    {
        $validatedData = $request->validated();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return $user;
    }

    public function editProfile(EditUserProfileRequest $request, User $user)
    {
        $validatedData = $request->validated();

        if (isset($validatedData['color_theme'])) {
            $user->color_theme = $validatedData['color_theme'];
        }

        if (isset($validatedData['avatarUrl'])) {
            if($user->avatar){
                Storage::delete($user->avatar);
            }

            $user->avatar = Storage::url($validatedData['avatarUrl']);
        }

        $user->save();
    }

    public function countUsers()
    {
        return User::count();
    }
}
