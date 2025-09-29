<div class="space-y-6">
    <!-- Header Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-900 mb-2">Event Details</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Timestamp</dt>
                    <dd class="text-sm text-gray-900">{{ $record->created_at->format('M j, Y \a\t g:i:s A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Action</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($record->action)
                                @case('create') bg-green-100 text-green-800 @break
                                @case('update') bg-yellow-100 text-yellow-800 @break
                                @case('delete') bg-red-100 text-red-800 @break
                                @case('view') bg-blue-100 text-blue-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ \App\Models\AuditTrail::getActions()[$record->action] ?? $record->action }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Resource</dt>
                    <dd class="text-sm text-gray-900">{{ $record->resource_type_name }}</dd>
                </div>
                @if($record->resource_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Item</dt>
                    <dd class="text-sm text-gray-900">{{ $record->resource_name }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg">
            <h3 class="font-semibold text-gray-900 mb-2">User & Context</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">User</dt>
                    <dd class="text-sm text-gray-900">{{ $record->user_display_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                    <dd class="text-sm text-gray-900 font-mono">{{ $record->ip_address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($record->category)
                                @case('sensitive') bg-red-500 text-red-800 @break
                                @case('financial') bg-yellow-100 text-yellow-800 @break
                                @case('personal') bg-blue-100 text-blue-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ \App\Models\AuditTrail::getCategories()[$record->category] ?? $record->category }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Severity</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($record->severity)
                                @case('critical') bg-red-100 text-red-800 @break
                                @case('high') bg-orange-100 text-orange-800 @break
                                @case('medium') bg-yellow-100 text-yellow-800 @break
                                @case('low') bg-green-100 text-green-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch">
                            {{ \App\Models\AuditTrail::getSeverityLevels()[$record->severity] ?? $record->severity }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @if($record->description)
    <!-- Description -->
    <div class="bg-gray-800 p-4 rounded-lg">
        <h3 class="font-semibold text-gray-900 mb-2">Description</h3>
        <p class="text-sm text-gray-700">{{ $record->description }}</p>
    </div>
    @endif

    @if($record->old_values || $record->new_values)
    <!-- Data Changes -->
    <div class="bg-gray-800 p-4 rounded-lg">
        <h3 class="font-semibold text-gray-900 mb-2">Data Changes</h3>

        @if($record->old_values)
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Previous Values</h4>
            <pre class="bg-gray-700 text-gray-900 p-3 rounded border text-xs overflow-x-auto">{{ json_encode($record->old_values, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        @if($record->new_values)
        <div>
            <h4 class="text-sm font-medium text-gray-700 mb-2">New Values</h4>
            <pre class="bg-gray-700 text-gray-900 p-3 rounded border text-xs overflow-x-auto">{{ json_encode($record->new_values, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
    @endif

    @if($record->context)
    <!-- Additional Context -->
    <div class="bg-gray-800 p-4 rounded-lg">
        <h3 class="font-semibold text-gray-900 mb-2">Additional Context</h3>
        <pre class="bg-gray-700 text-gray-900 p-3 rounded border text-xs overflow-x-auto">{{ json_encode($record->context, JSON_PRETTY_PRINT) }}</pre>
    </div>
    @endif

    <!-- Technical Details -->
    <div class="bg-gray-700 p-4 rounded-lg">
        <h3 class="font-semibold text-gray-900 mb-2">Technical Details</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">URL</dt>
                <dd class="text-sm text-gray-900 break-all">{{ $record->url ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">HTTP Method</dt>
                <dd class="text-sm text-gray-900">{{ $record->method ?? 'N/A' }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                <dd class="text-sm text-gray-900 break-all">{{ $record->user_agent ?? 'N/A' }}</dd>
            </div>
        </dl>
    </div>

    @if($record->is_sensitive)
    <!-- Sensitive Data Warning -->
    <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-red-800">This audit log contains sensitive data.</span>
        </div>
    </div>
    @endif
</div>
