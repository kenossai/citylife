<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Change Password - {{ config('app.name') }}</title>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="h-full antialiased">
    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="mx-auto h-20 w-auto">
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    @if(auth()->check() && auth()->user()->force_password_change)
                        You must change your password before continuing
                    @else
                        Change Your Password
                    @endif
                </h2>
            </div>

            <!-- Content -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
