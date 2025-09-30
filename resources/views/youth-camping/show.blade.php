@extends('layouts.app')

@section('title', $youthCamping->name . ' - Youth Camping')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('youth-camping.index') }}" class="hover:text-blue-600">Youth Camping</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800 font-medium">{{ $youthCamping->name }}</li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg p-8 mb-8">
        <div class="max-w-4xl">
            <h1 class="text-4xl font-bold mb-4">{{ $youthCamping->name }}</h1>
            <p class="text-xl mb-4">{{ $youthCamping->year }} • {{ $youthCamping->location }}</p>
            <div class="flex flex-wrap items-center gap-6 text-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $youthCamping->start_date->format('M j') }} - {{ $youthCamping->end_date->format('M j, Y') }}
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    ${{ number_format($youthCamping->cost, 2) }}
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                    </svg>
                    {{ $youthCamping->available_spots }} spots available
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="text-green-600 mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-green-800">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="text-red-600 mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-red-800">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">About This Camping</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($youthCamping->description)) !!}
                </div>
            </div>

            <!-- Schedule & Activities -->
            @if($youthCamping->schedule)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Schedule & Activities</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($youthCamping->schedule)) !!}
                    </div>
                </div>
            @endif

            <!-- What to Bring -->
            @if($youthCamping->what_to_bring)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">What to Bring</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($youthCamping->what_to_bring)) !!}
                    </div>
                </div>
            @endif

            <!-- Rules & Policies -->
            @if($youthCamping->rules_policies)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Rules & Policies</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($youthCamping->rules_policies)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Registration Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Registration Status</h3>

                @if($youthCamping->is_registration_available)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <div class="text-green-600 mr-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-green-800 font-semibold">Open for Registration</span>
                        </div>
                        <p class="text-green-700 text-sm">Registration closes on {{ $youthCamping->registration_closes_at->format('F j, Y') }}</p>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <div class="text-yellow-600 mr-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-yellow-800 font-semibold">{{ $youthCamping->registration_status_message }}</span>
                        </div>
                        @if($youthCamping->registration_opens_at > now())
                            <p class="text-yellow-700 text-sm">Registration opens on {{ $youthCamping->registration_opens_at->format('F j, Y') }}</p>
                        @endif
                    </div>
                @endif

                <!-- Action Button -->
                @if($youthCamping->is_registration_available)
                    <a href="{{ route('youth-camping.register', $youthCamping) }}"
                       class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                        Register Your Child
                    </a>
                @else
                    <button disabled class="block w-full text-center px-6 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed">
                        Registration Not Available
                    </button>
                @endif
            </div>

            <!-- Quick Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Details</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dates:</span>
                        <span class="font-semibold">{{ $youthCamping->start_date->format('M j') }} - {{ $youthCamping->end_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-semibold">{{ $youthCamping->location }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cost:</span>
                        <span class="font-semibold">${{ number_format($youthCamping->cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Participants:</span>
                        <span class="font-semibold">{{ $youthCamping->max_participants }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Available Spots:</span>
                        <span class="font-semibold {{ $youthCamping->available_spots <= 5 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $youthCamping->available_spots }}
                        </span>
                    </div>
                    @if($youthCamping->registration_opens_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registration Opens:</span>
                            <span class="font-semibold">{{ $youthCamping->registration_opens_at->format('M j, Y') }}</span>
                        </div>
                    @endif
                    @if($youthCamping->registration_closes_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registration Closes:</span>
                            <span class="font-semibold">{{ $youthCamping->registration_closes_at->format('M j, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Questions?</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="text-gray-600 mr-3 mt-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Email</div>
                            <div class="font-semibold">youth@citylifechurch.com</div>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="text-gray-600 mr-3 mt-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Phone</div>
                            <div class="font-semibold">(555) 123-4567</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            For questions about registration, activities, or medical requirements,
                            please contact our youth ministry team.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
