<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMessageOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $messageOwnerId = $request->route('message')['user_id'];
        $currentUserId = Auth::id();
        $isAdmin = Auth::check() && Auth::user()->isAdmin();
        $canManageMessage = $messageOwnerId === $currentUserId || $isAdmin;

        if($canManageMessage){
            return $next($request);
        }

        return ApiResponse::error(['message' => "You can't manage a message that isn't yours"], 403);
    }
}
