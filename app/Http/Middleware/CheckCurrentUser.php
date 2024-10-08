<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCurrentUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->route('user')['id'];
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $canManageUserProfile = $isAdmin || (Auth::check() && Auth::id() === (int)$userId);

        if (!$canManageUserProfile) {
            return ApiResponse::error(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
