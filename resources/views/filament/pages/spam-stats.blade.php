<div class="space-y-4">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Blocked IPs</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBlocked }}</div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm text-green-600 dark:text-green-400">Active Blocks</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $activeBlocked }}</div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
            <div class="text-sm text-red-600 dark:text-red-400">Total Attempts</div>
            <div class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $totalAttempts }}</div>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
            <div class="text-sm text-orange-600 dark:text-orange-400">Auto-Blocked</div>
            <div class="text-2xl font-bold text-orange-700 dark:text-orange-300">{{ $autoBlocked }}</div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 col-span-2">
            <div class="text-sm text-blue-600 dark:text-blue-400">Blocked in Last 7 Days</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $recentBlocks }}</div>
        </div>
    </div>

    @if($topOffenders->isNotEmpty())
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Top Offenders</h3>
            <div class="space-y-2">
                @foreach($topOffenders as $offender)
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div>
                            <div class="font-mono text-sm text-gray-900 dark:text-white">{{ $offender->ip_address }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-md">{{ $offender->reason }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                {{ $offender->spam_count }} attempts
                            </span>
                            @if($offender->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
