<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Standard Success Response (200 OK).
     */
    public static function success(string $message = 'Action performed successfully.', mixed $data = null, int $code = 200): JsonResponse
    {
        $response = ['success' => true, 'message' => $message];

        if (is_array($data)) {
            $response = array_merge($response, $data);
            if (!isset($response['data'])) {
                $response['data'] = $data;
            }
        } elseif ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Standard Creation Response (201 Created).
     */
    public static function created(string $message = 'Resource created successfully.', mixed $data = null): JsonResponse
    {
        $response = ['success' => true, 'message' => $message];

        if (is_array($data)) {
            $response = array_merge($response, $data);
            if (!isset($response['data'])) {
                $response['data'] = $data;
            }
        } elseif ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, 201);
    }

    /**
     * Standard Deletion / Deactivation Response (200 OK).
     */
    public static function deleted(string $message = 'Resource deleted successfully.'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * Standard Action / Generic Error Response (400, 403, 500, etc.).
     */
    public static function error(string $message = 'An error occurred.', int $code = 400, mixed $errors = null): JsonResponse
    {
        if ($code === 422) {
            return self::validationError($message, $errors ?? []);
        }

        $response = ['success' => false, 'message' => $message];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Standard Validation Error Response (422 Unprocessable Entity).
     */
    public static function validationError(string $message = 'The given data was invalid.', mixed $errors = []): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }
}
