<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;
use \Exception;
use Core\Exceptions\PersonTypeInvalidException;
use Core\Exceptions\DataNotFoundException;

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
        ], $this->statusCodeByException($exception));
    }

    /**
     * Map status http code error by exceptions
     *
     * @param Exception $exception
     * @return int
     */
    private function statusCodeByException(Exception $exception): int
    {
        switch ($exception) {
            case $exception instanceof PersonTypeInvalidException:
                return 400;
            case $exception instanceof DataNotFoundException:
                return 404;
            default:
                return 500;
        }
    }
}
