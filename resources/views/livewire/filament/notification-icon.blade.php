<div wire:poll.30s="refreshCounts" class="flex items-center gap-4 mr-4">
    <!-- Mail Icon -->
    @if($mailCount > 0)
        <a href="{{ route('filament.admin.resources.mail-managers.index') }}" 
           class="relative inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
            <x-heroicon-o-envelope class="w-6 h-6" />
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $mailCount }}
            </span>
        </a>
    @endif

    <!-- Volunteer Icon -->
    @if($volunteerCount > 0)
        <a href="{{ route('filament.admin.resources.volunteer-applications.index') }}" 
           class="relative inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
            <x-heroicon-o-user-group class="w-6 h-6" />
            <span class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $volunteerCount }}
            </span>
        </a>
    @endif

    <!-- Combined Bell Icon -->
    @if($totalCount > 0)
        <div class="relative inline-flex items-center text-gray-600">
            <x-heroicon-o-bell class="w-6 h-6" />
            <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $totalCount }}
            </span>
        </div>
    @endif
</div>
