<div class="p-6">
    <div class="space-y-4">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                📧 Message Preview
            </h3>
            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                <p><strong>To:</strong> {{ implode(', ', $reminder->notification_recipients ?? ['admin@citylifecc.com']) }}</p>
                <p><strong>Subject:</strong> {{ $reminder->reminder_type_label }} Reminder</p>
                <p><strong>Scheduled for:</strong> {{ $reminder->notification_date->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
            <div class="prose dark:prose-invert max-w-none">
                <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ $reminder->reminder_type_label }} Reminder
                </h4>

                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 p-4 mb-4">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        {{ $reminder->formatted_message }}
                    </p>
                </div>

                @if($reminder->description)
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes:</h5>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $reminder->description }}</p>
                    </div>
                @endif

                <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Member:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $reminder->member->first_name }} {{ $reminder->member->last_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Date:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $reminder->reminder_date->format('F j, Y') }}</span>
                    </div>
                    @if($reminder->years_count)
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Years:</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ $reminder->years_count }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="font-medium text-gray-700 dark:text-gray-300">Reminder Days:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $reminder->days_before_reminder }} days before</span>
                    </div>
                </div>

                @if($reminder->member->phone)
                    <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            <strong>Member Contact:</strong> {{ $reminder->member->phone }}
                            @if($reminder->member->email)
                                | {{ $reminder->member->email }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
