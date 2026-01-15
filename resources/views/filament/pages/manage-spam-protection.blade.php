<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-600">
                        {{ count(config('spam-protection.blocked_ips', [])) }}
                    </div>
                    <div class="text-sm text-gray-500">Blocked IP Addresses</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning-600">
                        {{ count(config('spam-protection.disposable_email_domains', [])) }}
                    </div>
                    <div class="text-sm text-gray-500">Blocked Email Domains</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-3xl font-bold text-danger-600">
                        {{ count(config('spam-protection.suspicious_patterns', [])) }}
                    </div>
                    <div class="text-sm text-gray-500">Spam Detection Patterns</div>
                </div>
            </x-filament::card>
        </div>

        <!-- Blocked IPs -->
        <x-filament::card>
            <x-slot name="heading">
                Blocked IP Addresses
            </x-slot>

            <div class="space-y-2">
                @forelse(config('spam-protection.blocked_ips', []) as $ip)
                    <div class="flex items-center justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-danger-100 dark:bg-danger-900">
                                <x-heroicon-o-shield-exclamation class="h-4 w-4 text-danger-600 dark:text-danger-400" />
                            </div>
                            <div>
                                <div class="font-mono text-sm font-semibold">{{ $ip }}</div>
                                <div class="text-xs text-gray-500">Permanently blocked</div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            From config file
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center dark:border-gray-600">
                        <x-heroicon-o-shield-check class="mx-auto h-12 w-12 text-gray-400" />
                        <div class="mt-4 text-sm text-gray-500">No IP addresses blocked yet</div>
                    </div>
                @endforelse
            </div>
        </x-filament::card>

        <!-- Settings Info -->
        <x-filament::card>
            <x-slot name="heading">
                Protection Settings
            </x-slot>

            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <div>
                        <div class="font-medium">Rate Limiting</div>
                        <div class="text-sm text-gray-500">Maximum submissions per hour from same IP</div>
                    </div>
                    <div class="font-mono font-semibold text-primary-600">
                        {{ config('spam-protection.rate_limit_per_hour', 3) }}
                    </div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <div>
                        <div class="font-medium">Minimum Form Time</div>
                        <div class="text-sm text-gray-500">Minimum seconds to fill out form</div>
                    </div>
                    <div class="font-mono font-semibold text-primary-600">
                        {{ config('spam-protection.minimum_form_time', 3) }}s
                    </div>
                </div>

                <div class="flex items-center justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                    <div>
                        <div class="font-medium">Maximum URLs</div>
                        <div class="text-sm text-gray-500">Maximum URLs allowed in message</div>
                    </div>
                    <div class="font-mono font-semibold text-primary-600">
                        {{ config('spam-protection.max_urls_in_message', 2) }}
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Spam Protection</div>
                        <div class="text-sm text-gray-500">Overall protection status</div>
                    </div>
                    <div>
                        @if(config('spam-protection.enabled', true))
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-100 px-2 py-1 text-xs font-semibold text-success-700 dark:bg-success-900 dark:text-success-300">
                                <x-heroicon-o-check-circle class="h-4 w-4" />
                                Enabled
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-danger-100 px-2 py-1 text-xs font-semibold text-danger-700 dark:bg-danger-900 dark:text-danger-300">
                                <x-heroicon-o-x-circle class="h-4 w-4" />
                                Disabled
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </x-filament::card>

        <!-- Instructions -->
        <x-filament::card>
            <x-slot name="heading">
                How to Manage Spam Protection
            </x-slot>

            <div class="prose prose-sm dark:prose-invert max-w-none">
                <p>To modify spam protection settings, edit the configuration file:</p>
                <pre class="rounded-lg bg-gray-100 p-3 dark:bg-gray-800"><code>config/spam-protection.php</code></pre>
                
                <h4>Adding a Blocked IP:</h4>
                <ol>
                    <li>Open <code>config/spam-protection.php</code></li>
                    <li>Find the <code>'blocked_ips'</code> array</li>
                    <li>Add the IP address: <code>'123.456.789.012',</code></li>
                    <li>Save the file</li>
                    <li>Clear cache: <code>php artisan config:clear</code></li>
                </ol>

                <h4>Finding IPs to Block:</h4>
                <p>Check the Contact Submissions resource for suspicious messages and note their IP addresses.</p>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>
