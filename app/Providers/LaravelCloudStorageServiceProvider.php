<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class LaravelCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Parse Laravel Cloud disk configuration
            $diskConfig = env('LARAVEL_CLOUD_DISK_CONFIG');

            if ($diskConfig) {
                $disks = json_decode($diskConfig, true);

                if (is_array($disks)) {
                    foreach ($disks as $diskData) {
                        $diskName = $diskData['disk'] ?? 'private';

                        // Configure the disk dynamically
                        Config::set("filesystems.disks.{$diskName}", [
                            'driver' => 's3',
                            'key' => $diskData['access_key_id'] ?? '',
                            'secret' => $diskData['access_key_secret'] ?? '',
                            'region' => $diskData['default_region'] ?? 'auto',
                            'bucket' => $diskData['bucket'] ?? '',
                            'url' => $diskData['url'] ?? null,
                            'endpoint' => $diskData['endpoint'] ?? null,
                            'use_path_style_endpoint' => $diskData['use_path_style_endpoint'] ?? false,
                            'throw' => false,
                            'report' => false,
                            'visibility' => 'public',
                        ]);

                        // DON'T set as default - let it stay as 'public' for now
                        // This prevents breaking the site if R2 has issues
                        // if ($diskData['is_default'] ?? false) {
                        //     Config::set('filesystems.default', $diskName);
                        // }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail - don't break the site if R2 config fails
            \Log::error('LaravelCloudStorageServiceProvider failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
