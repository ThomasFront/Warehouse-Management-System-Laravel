<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return Auth::login($user);
    }

    public function login(array $credentials): ?array
    {
        $token = Auth::attempt($credentials);

        if (!$token) {
            return null;
        }

        $user = Auth::user();

        return [
            'user' => $user,
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
