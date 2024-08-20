<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request){

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'token' => $token,
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function me()
    {
        $user = Auth::user();

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out.',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => new UserResource(Auth::user()),
            'token' => Auth::refresh(),
        ]);
    }

}
