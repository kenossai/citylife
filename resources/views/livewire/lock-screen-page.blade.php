<div class="fi-simple-page">
    <section class="grid auto-cols-fr gap-y-6">
        <header class="fi-simple-header flex flex-col items-center">
            <x-filament-panels::logo class="mb-4" />

            <div class="mb-4 flex flex-col items-center">
                @if($lockedUser && $lockedUser->avatar)
                    <img src="{{ $lockedUser->avatar }}"
                         alt="{{ $lockedUser->name }}"
                         class="h-20 w-20 rounded-full ring-4 ring-white dark:ring-gray-900">
                @elseif($lockedUser)
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-2xl font-bold text-white ring-4 ring-white dark:ring-gray-900">
                        {{ strtoupper(substr($lockedUser->name, 0, 2)) }}
                    </div>
                @endif
            </div>

            <h1 class="fi-simple-header-heading text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                {{ $lockedUser?->name ?? 'User' }}
            </h1>

            <p class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                Your session was locked due to inactivity
            </p>
        </header>

        <form wire:submit="unlock" class="grid gap-y-6">
            {{ $this->form }}

            <x-filament::button type="submit" class="w-full">
                Unlock
            </x-filament::button>
        </form>

        <div class="text-center">
            <button
                wire:click="logout"
                type="button"
                class="fi-link group/link relative inline-flex items-center justify-center outline-none text-sm text-primary-600 hover:text-primary-500 focus-visible:underline dark:text-primary-400 dark:hover:text-primary-300"
            >
                Sign in as a different user
            </button>
        </div>
    </section>
</div>
