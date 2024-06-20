<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;
use \Exception;

class BaseApiController extends Controller
{
    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse(string $message, array $data = [], $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse(Exception $exception): JsonResponse
    {
        return response()->json([
            'error' => $exception->getMessage()
        ], $exception->getCode());
    }
}
