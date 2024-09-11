<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(array $data)
    {
        User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);
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
