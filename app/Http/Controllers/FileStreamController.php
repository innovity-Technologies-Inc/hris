<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileStreamController extends Controller
{
    /**
     * Securely stream a private file from MinIO/S3 through the application.
     *
     * The file path is base64-encoded in the URL to safely handle slashes and
     * special characters without needing complex route patterns.
     *
     * Route: GET /file/serve/{encodedPath}
     */
    public function serve(Request $request, string $encodedPath): StreamedResponse
    {
        // Decode the base64-encoded path
        $path = base64_decode($encodedPath);

        // Basic path traversal protection
        if (!$path || str_contains($path, '..')) {
            abort(400, 'Invalid file path.');
        }

        $disk = config('filesystems.default', 'public');

        // Normalize: local disk should proxy via public disk
        if ($disk === 'local') {
            $disk = 'public';
        }

        // Check file existence
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        // Stream the file directly to the browser
        return Storage::disk($disk)->response($path);
    }
}
