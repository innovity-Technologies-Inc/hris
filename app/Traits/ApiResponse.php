<?php

namespace App\Traits;

use App\Http\Responses\ApiResponse as ResponseHelper;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a 200 OK success JSON response.
     */
    protected function successResponse(string $message = 'Action performed successfully.', mixed $data = null, int $code = 200): JsonResponse
    {
        return ResponseHelper::success($message, $data, $code);
    }

    /**
     * Return a 201 Created JSON response.
     */
    protected function createdResponse(string $message = 'Resource created successfully.', mixed $data = null): JsonResponse
    {
        return ResponseHelper::created($message, $data);
    }

    /**
     * Return a 200 OK deletion JSON response.
     */
    protected function deletedResponse(string $message = 'Resource deleted successfully.'): JsonResponse
    {
        return ResponseHelper::deleted($message);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message = 'An error occurred.', int $code = 400, mixed $errors = null): JsonResponse
    {
        return ResponseHelper::error($message, $code, $errors);
    }

    /**
     * Return a 422 Unprocessable Entity validation error JSON response.
     */
    protected function validationErrorResponse(string $message = 'The given data was invalid.', mixed $errors = []): JsonResponse
    {
        return ResponseHelper::validationError($message, $errors);
    }
}
