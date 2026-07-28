<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileStreamController extends Controller
{
    /**
     * SOLUTION 1 (Kept for reference / fallback):
     * Securely stream a private file from MinIO/S3 THROUGH PHP.
     *
     * PHP reads the file from MinIO and pipes it to the browser.
     * Simpler but uses more PHP memory/CPU for large files.
     *
     * Route: GET /file/serve/{encodedPath}
     */
    public function serve(Request $request, string $encodedPath): StreamedResponse
    {
        $path = base64_decode($encodedPath);

        if (!$path || str_contains($path, '..')) {
            abort(400, 'Invalid file path.');
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 'local') {
            $disk = 'public';
        }

        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk($disk)->response($path);
    }

    /**
     * SOLUTION 2 (ACTIVE — High Performance):
     * Serve a private MinIO/S3 file via Nginx X-Accel-Redirect.
     *
     * How it works:
     *  1. PHP authenticates the request (via auth middleware).
     *  2. PHP generates a short-lived pre-signed URL for the file on MinIO.
     *  3. PHP returns an empty 200 response with the X-Accel-Redirect header.
     *  4. Nginx intercepts the header, strips the /minio-internal/ prefix,
     *     and fetches the file directly from MinIO internally — bypassing PHP entirely.
     *  5. The signed query parameters ensure MinIO accepts the anonymous fetch.
     *
     * Result: PHP only handles auth + URL signing (~milliseconds).
     *         Nginx + MinIO handle all the heavy file transfer.
     *
     * Route: GET /file/accel/{encodedPath}
     */
    public function serveViaAccel(Request $request, string $encodedPath): Response
    {
        $path = base64_decode($encodedPath);

        if (!$path || str_contains($path, '..')) {
            abort(400, 'Invalid file path.');
        }

        $disk = config('filesystems.default', 'public');

        // Verify the file actually exists on the MinIO/S3 disk
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        // Generate a short-lived pre-signed URL (1 minute is enough —
        // Nginx fetches it immediately after PHP responds)
        $presignedUrl = Storage::disk($disk)->temporaryUrl($path, now()->addMinutes(1));

        // Parse the pre-signed URL into path + query string components
        $parsed    = parse_url($presignedUrl);
        $signedUri = ($parsed['path'] ?? '') . '?' . ($parsed['query'] ?? '');

        // Tell Nginx to handle the actual file delivery.
        // /minio-internal maps to the internal Nginx proxy block that
        // forwards directly to MinIO using the signed credentials.
        return response('', 200)->header('X-Accel-Redirect', '/minio-internal' . $signedUri);
    }
}
