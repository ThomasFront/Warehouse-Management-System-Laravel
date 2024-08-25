<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data, $status = 200): JsonResponse
    {
        return response()->json([
            'data' => $data
        ], $status);
    }

    public static function error($message, $status = 400): JsonResponse
    {
        return response()->json([
            'error' => $message
        ], $status);
    }
}

