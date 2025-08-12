@php
    $mailCount = \App\Models\ContactSubmission::where('status', 'new')->count();
    $volunteerCount = \App\Models\VolunteerApplication::where('status', 'pending')->count();
    $totalCount = $mailCount + $volunteerCount;
@endphp

<div class="flex items-center gap-3 ml-4">
    <!-- Mail Icon -->
    @if($mailCount > 0)
        <a href="{{ route('filament.admin.resources.mail-managers.index') }}" 
           class="relative inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors"
           title="New mail messages: {{ $mailCount }}">
            <x-heroicon-o-envelope class="w-5 h-5" />
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-medium">
                {{ $mailCount }}
            </span>
        </a>
    @endif

    <!-- Volunteer Icon -->
    @if($volunteerCount > 0)
        <a href="{{ route('filament.admin.resources.volunteer-applications.index') }}" 
           class="relative inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors"
           title="Pending volunteer applications: {{ $volunteerCount }}">
            <x-heroicon-o-user-group class="w-5 h-5" />
            <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-medium">
                {{ $volunteerCount }}
            </span>
        </a>
    @endif

    <!-- Combined Bell Icon (only show if there are notifications) -->
    @if($totalCount > 0)
        <div class="relative inline-flex items-center text-gray-500" title="Total notifications: {{ $totalCount }}">
            <x-heroicon-o-bell class="w-5 h-5" />
            <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-medium">
                {{ $totalCount }}
            </span>
        </div>
    @endif
</div>
