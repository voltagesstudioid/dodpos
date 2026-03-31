<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileUploadService
{
    /**
     * Allowed MIME types for images.
     */
    private const ALLOWED_IMAGE_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    /**
     * Allowed MIME types for documents.
     */
    private const ALLOWED_DOCUMENT_MIMES = [
        'text/csv' => 'csv',
        'text/plain' => 'txt',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/json' => 'json',
    ];

    /**
     * Maximum file sizes in KB.
     */
    private const MAX_IMAGE_SIZE_KB = 4096;  // 4MB
    private const MAX_DOCUMENT_SIZE_KB = 20480;  // 20MB

    /**
     * Securely upload an image file.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @param array $options ['max_width' => 2000, 'max_height' => 2000, 'strip_exif' => true]
     * @return array ['path' => string, 'filename' => string, 'url' => string|null]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function uploadImage(
        UploadedFile $file,
        string $directory,
        string $disk = 'public',
        array $options = []
    ): array {
        // Validate actual MIME type
        $mimeType = $file->getMimeType();
        if (! isset(self::ALLOWED_IMAGE_MIMES[$mimeType])) {
            throw new \InvalidArgumentException('File type tidak diizinkan. Hanya JPG, PNG, dan WebP yang diperbolehkan.');
        }

        // Validate size
        if ($file->getSize() > self::MAX_IMAGE_SIZE_KB * 1024) {
            throw new \InvalidArgumentException('Ukuran file maksimal ' . self::MAX_IMAGE_SIZE_KB . ' KB.');
        }

        // Generate cryptographically secure random filename
        $extension = self::ALLOWED_IMAGE_MIMES[$mimeType];
        $filename = self::generateSecureFilename($extension);
        $path = $directory . '/' . $filename;

        // Process image if needed
        if (! empty($options['max_width']) || ! empty($options['max_height']) || ! empty($options['strip_exif'])) {
            try {
                $image = Image::read($file->getRealPath());

                // Resize if too large
                if (! empty($options['max_width']) || ! empty($options['max_height'])) {
                    $maxWidth = $options['max_width'] ?? null;
                    $maxHeight = $options['max_height'] ?? null;
                    $image->scaleDown($maxWidth, $maxHeight);
                }

                // Strip EXIF data for privacy
                $image->strip();

                // Save processed image
                Storage::disk($disk)->put($path, (string) $image->encode());
            } catch (\Exception $e) {
                Log::error('Image processing failed', ['error' => $e->getMessage()]);
                // Fall back to direct storage
                Storage::disk($disk)->putFileAs($directory, $file, $filename);
            }
        } else {
            Storage::disk($disk)->putFileAs($directory, $file, $filename);
        }

        return [
            'path' => $path,
            'filename' => $filename,
            'url' => $disk === 'public' ? Storage::disk($disk)->url($path) : null,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
        ];
    }

    /**
     * Securely upload a document file.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @param array $allowedTypes Override allowed MIME types
     * @return array ['path' => string, 'filename' => string]
     * @throws \InvalidArgumentException
     */
    public static function uploadDocument(
        UploadedFile $file,
        string $directory,
        string $disk = 'private',
        array $allowedTypes = []
    ): array {
        $allowedMimes = $allowedTypes ?: self::ALLOWED_DOCUMENT_MIMES;
        $mimeType = $file->getMimeType();

        if (! isset($allowedMimes[$mimeType])) {
            throw new \InvalidArgumentException(
                'File type tidak diizinkan. Jenis yang diperbolehkan: ' . implode(', ', array_values($allowedMimes))
            );
        }

        // Validate size
        $maxSize = $disk === 'private' ? self::MAX_DOCUMENT_SIZE_KB : self::MAX_IMAGE_SIZE_KB;
        if ($file->getSize() > $maxSize * 1024) {
            throw new \InvalidArgumentException('Ukuran file maksimal ' . $maxSize . ' KB.');
        }

        // Basic content validation for CSV/TXT files
        if (in_array($mimeType, ['text/csv', 'text/plain'])) {
            $content = file_get_contents($file->getRealPath());
            // Check for PHP tags or executable content
            if (preg_match('/<\?php|<\?=|<\?/i', $content)) {
                throw new \InvalidArgumentException('File mengandung kode yang tidak diizinkan.');
            }
        }

        $extension = $allowedMimes[$mimeType];
        $filename = self::generateSecureFilename($extension);
        $path = $directory . '/' . $filename;

        Storage::disk($disk)->putFileAs($directory, $file, $filename);

        return [
            'path' => $path,
            'filename' => $filename,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
        ];
    }

    /**
     * Delete an uploaded file.
     */
    public static function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    /**
     * Generate a cryptographically secure random filename.
     */
    private static function generateSecureFilename(string $extension): string
    {
        $random = bin2hex(random_bytes(16));  // 32 chars
        $timestamp = now()->format('YmdHis');

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Validate image dimensions.
     */
    public static function validateImageDimensions(
        UploadedFile $file,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?int $minWidth = null,
        ?int $minHeight = null
    ): void {
        try {
            $image = Image::read($file->getRealPath());
            $width = $image->width();
            $height = $image->height();

            if ($maxWidth && $width > $maxWidth) {
                throw new \InvalidArgumentException("Lebar gambar maksimal {$maxWidth}px.");
            }
            if ($maxHeight && $height > $maxHeight) {
                throw new \InvalidArgumentException("Tinggi gambar maksimal {$maxHeight}px.");
            }
            if ($minWidth && $width < $minWidth) {
                throw new \InvalidArgumentException("Lebar gambar minimal {$minWidth}px.");
            }
            if ($minHeight && $height < $minHeight) {
                throw new \InvalidArgumentException("Tinggi gambar minimal {$minHeight}px.");
            }
        } catch (\Exception $e) {
            if ($e instanceof \InvalidArgumentException) {
                throw $e;
            }
            Log::error('Image dimension validation failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Sanitize filename to prevent path traversal.
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path traversal characters
        $filename = str_replace(['../', '..\\', '\\', '/', ':', '*', '?', '"', '<', '>', '|'], '', $filename);

        // Keep only alphanumeric, dash, underscore, and dot
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        // Ensure it doesn't start with dot
        $filename = ltrim($filename, '.');

        return $filename ?: 'unnamed';
    }
}
