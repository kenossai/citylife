<x-filament-panels::page>
    <div class="flex justify-center mb-8">
        <img src="{{ url('assets/images/logo_small_white.png') }}" alt="CityLife Church" class="h-12">
    </div>

    @if(auth()->user()->force_password_change)
        <div class="mb-6 p-4 bg-warning-50 border-l-4 border-warning-500 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400 dark:border-warning-600 rounded-r">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-warning-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Security Notice</h3>
                    <div class="mt-2 text-sm">
                        <p>For security reasons, you must change your temporary password before accessing the system.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="changePassword">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit" size="lg" class="w-full">
                Update Password
            </x-filament::button>
        </div>
    </form>

    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Password Requirements:</h4>
        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1 list-disc list-inside">
            <li>At least 8 characters long</li>
            <li>Mix of uppercase and lowercase letters</li>
            <li>Include at least one number</li>
            <li>Include at least one special character</li>
        </ul>
    </div>
</x-filament-panels::page>
