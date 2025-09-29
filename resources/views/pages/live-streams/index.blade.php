<x-app-layout>
    @section('title', 'Live Streams')
    @section('meta_description', 'Watch our live church services and events. Join us online for worship, prayer meetings, and special programs.')

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-br from-blue-600 to-purple-700 text-white">
            <div class="container mx-auto px-4 py-16">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Live Streams</h1>
                    <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
                        Join us online for worship, prayer, and fellowship
                    </p>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <!-- Currently Live Streams -->
            @if($liveStreams->isNotEmpty())
            <div class="mb-16">
                <div class="flex items-center mb-8">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse mr-3"></div>
                        <h2 class="text-3xl font-bold text-gray-900">Live Now</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($liveStreams as $stream)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <!-- Live Stream Embed -->
                        <div class="aspect-video bg-black relative">
                            @if($stream->embed_code)
                                <div class="w-full h-full">
                                    {!! $stream->embed_code !!}
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-semibold">Live Stream</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Live Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold flex items-center">
                                    <div class="w-2 h-2 bg-white rounded-full animate-pulse mr-2"></div>
                                    LIVE
                                </span>
                            </div>

                            <!-- Viewer Count -->
                            @if($stream->estimated_viewers > 0)
                            <div class="absolute top-4 right-4">
                                <span class="bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                    {{ number_format($stream->estimated_viewers) }} viewers
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Stream Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $stream->title }}</h3>
                            @if($stream->description)
                                <p class="text-gray-600 mb-4">{{ Str::limit($stream->description, 120) }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        {{ LiveStream::getCategories()[$stream->category] ?? $stream->category }}
                                    </span>
                                    <span>{{ $stream->platform }}</span>
                                </div>

                                <a href="{{ route('live-streams.show', $stream->slug) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Watch Full Screen
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Featured Streams -->
            @if($featuredStreams->isNotEmpty())
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Streams</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredStreams as $stream)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <!-- Thumbnail -->
                        <div class="aspect-video bg-gray-200 relative overflow-hidden">
                            @if($stream->thumbnail_url)
                                <img src="{{ $stream->thumbnail_url }}" alt="{{ $stream->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 left-3">
                                @if($stream->status === 'live')
                                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">LIVE</span>
                                @elseif($stream->status === 'scheduled')
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-bold">UPCOMING</span>
                                @endif
                            </div>
                        </div>

                        <!-- Stream Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $stream->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($stream->description, 80) }}</p>

                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">
                                    {{ LiveStream::getCategories()[$stream->category] ?? $stream->category }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $stream->scheduled_start->format('M j, g:i A') }}
                                </span>
                            </div>

                            <a href="{{ route('live-streams.show', $stream->slug) }}"
                               class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                @if($stream->status === 'live')
                                    Watch Now
                                @else
                                    View Details
                                @endif
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upcoming Streams -->
            @if($upcomingStreams->isNotEmpty())
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Upcoming Streams</h2>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="divide-y divide-gray-200">
                        @foreach($upcomingStreams as $stream)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $stream->title }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($stream->description, 100) }}</p>

                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                            {{ LiveStream::getCategories()[$stream->category] ?? $stream->category }}
                                        </span>
                                        <span>{{ $stream->platform }}</span>
                                        <span>{{ $stream->scheduled_start->format('M j, Y \a\t g:i A') }}</span>
                                    </div>
                                </div>

                                <div class="ml-6 text-right">
                                    <div class="text-sm text-gray-500 mb-2">{{ $stream->time_until_start }}</div>
                                    <a href="{{ route('live-streams.show', $stream->slug) }}"
                                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        Set Reminder
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- No Streams Message -->
            @if($liveStreams->isEmpty() && $featuredStreams->isEmpty() && $upcomingStreams->isEmpty())
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Streams Available</h3>
                <p class="text-gray-600">Check back soon for upcoming live streams and events.</p>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-refresh page every 60 seconds to check for new live streams
        setInterval(function() {
            if (document.hidden === false) {
                location.reload();
            }
        }, 60000);
    </script>
    @endpush
</x-app-layout>
