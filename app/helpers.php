<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storage_url')) {
    /**
     * Get the URL for a storage file, using signed URLs for S3/R2
     *
     * @param string|null $path
     * @param string|null $disk
     * @return string
     */
    function storage_url(?string $path, ?string $disk = null): string
    {
        if (!$path) {
            return '';
        }

        $disk = $disk ?? config('filesystems.default');
        $storage = Storage::disk($disk);

        try {
            return $storage->url($path);
        } catch (\Exception $e) {
            \Log::error('Failed to generate storage URL', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage()
            ]);

            return '';
        }
    }
}
