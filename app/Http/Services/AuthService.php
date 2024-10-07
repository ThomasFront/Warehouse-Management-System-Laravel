<?php

namespace App\Http\Services;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{

    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'password' => Hash::make($validatedData['password']),
        ]);

        if (isset($validatedData['avatarUrl'])) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $user->avatar = Storage::url($validatedData['avatarUrl']);
            $user->save();
        }
    }

    public function login(array $credentials): ?array
    {
        $token = Auth::attempt($credentials);

        if (!$token) {
            return null;
        }

        return [
            'token' => $token
        ];
    }

    public function me(): User
    {
        return Auth::user();
    }

    public function logout()
    {
        return Auth::logout();
    }

    public function refreshToken(): array
    {
        return [
            'user' => Auth::user(),
            'token' => Auth::refresh()
        ];
    }
}
