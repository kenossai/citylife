<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CloudStorageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            $diskConfig = env('LARAVEL_CLOUD_DISK_CONFIG');

            if (!$diskConfig) {
                return;
            }

            $disks = json_decode($diskConfig, true);

            if (!is_array($disks) || json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse LARAVEL_CLOUD_DISK_CONFIG');
                return;
            }

            foreach ($disks as $diskData) {
                if (!isset($diskData['disk'])) {
                    continue;
                }

                $diskName = $diskData['disk'];

                // Configure the R2 disk
                $config = [
                    'driver' => 's3',
                    'key' => $diskData['access_key_id'] ?? '',
                    'secret' => $diskData['access_key_secret'] ?? '',
                    'region' => $diskData['default_region'] ?? 'auto',
                    'bucket' => $diskData['bucket'] ?? '',
                    'endpoint' => $diskData['endpoint'] ?? null,
                    'use_path_style_endpoint' => $diskData['use_path_style_endpoint'] ?? false,
                    'throw' => false,
                    'visibility' => 'public',
                ];

                // If URL is not provided, construct it from endpoint and bucket
                if (!empty($diskData['url'])) {
                    $config['url'] = $diskData['url'];
                } elseif (!empty($diskData['endpoint']) && !empty($diskData['bucket'])) {
                    // For R2, construct public URL from bucket name
                    $bucket = $diskData['bucket'];
                    $accountId = explode('.', parse_url($diskData['endpoint'], PHP_URL_HOST))[0] ?? '';
                    $config['url'] = "https://{$bucket}.{$accountId}.r2.cloudflarestorage.com";
                }

                Config::set("filesystems.disks.{$diskName}", $config);

                // Set as default if specified
                if (!empty($diskData['is_default'])) {
                    Config::set('filesystems.default', $diskName);
                    Log::info("Set {$diskName} as default filesystem disk");
                }
            }
        } catch (\Exception $e) {
            Log::error('CloudStorageServiceProvider error: ' . $e->getMessage());
        }
    }
}
