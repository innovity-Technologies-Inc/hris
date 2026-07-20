# Generic JSON API Response Class & Trait Requirements

## Overview
Implement a generic, reusable `ApiResponse` class and trait to standardize JSON response structures across all API controllers in accordance with `GEMINI.md`.

## Standardized JSON Structures

1. **Success (200 OK)**:
   - Payload: `{"success": true, "message": "...", "data": {...}}` (data is optional if empty).
2. **Creation (201 Created)**:
   - Payload: `{"success": true, "message": "Resource created successfully.", "data": {...}}`
3. **Deletion / Release (200 OK)**:
   - Payload: `{"success": true, "message": "Resource deleted/released/deactivated successfully."}`
4. **Error (400, 403, 500, etc.)**:
   - Payload: `{"success": false, "message": "Error message details here."}`
5. **Validation Error (422 Unprocessable Entity)**:
   - Payload: `{"message": "The given data was invalid.", "errors": {"field": ["Error message"]}}`

## Architecture

- **`App\Http\Responses\ApiResponse.php`**: Static helper class providing `success()`, `created()`, `deleted()`, `error()`, and `validationError()`.
- **`App\Traits\ApiResponse.php`**: Trait providing instance methods (`successResponse()`, `createdResponse()`, `deletedResponse()`, `errorResponse()`, `validationErrorResponse()`).
- **Base `Controller` (`App\Http\Controllers\Controller.php`)**: Includes `ApiResponse` trait for global accessibility.
- **Update Controllers**: Integrate `ApiResponse` into `DataController`, `LeavesController`, and `LeavePlanController`.
