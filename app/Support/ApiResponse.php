<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        string $message = 'Success',
        mixed $payload = null,
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'code'    => $code,
            'status'  => true,
            'message' => $message,
            'payload' => $payload,
        ], $code);
    }

    public static function error(
        string $message = 'Something went wrong',
        int $code = 400,
        mixed $payload = null
    ): JsonResponse {
        return response()->json([
            'code'    => $code,
            'status'  => false,
            'message' => $message,
            'payload' => $payload,
        ], $code);
    }
}
