<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterUserRequest $request)
    {
        $this->authService->register($request);

        return ApiResponse::success(['message' => 'User has been registered'], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $loginData = $this->authService->login($credentials);

        if (!$loginData) {
            return ApiResponse::error(['message' => 'Unauthorized.'], 401);
        }

        return ApiResponse::success(['token' => $loginData['token']]);
    }

    public function me()
    {
        $user = $this->authService->me();

        return ApiResponse::success(['user' => new UserResource($user)]);
    }

    public function logout()
    {
        $this->authService->logout();

        return ApiResponse::success(['message' => 'Successfully logged out.']);
    }

    public function refresh()
    {
        $result = $this->authService->refreshToken();

        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ]);
    }
}
