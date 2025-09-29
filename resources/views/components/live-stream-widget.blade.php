@if($liveStreams->isNotEmpty() || $upcomingStream)
<section class="py-16 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
        @if($liveStreams->isNotEmpty())
            @foreach($liveStreams as $stream)
            <!-- Currently Live Stream -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse mr-3"></div>
                    <h2 class="text-3xl md:text-4xl font-bold">We're Live Now!</h2>
                </div>
                <p class="text-xl text-gray-300 mb-8">{{ $stream->title }}</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Live Stream Embed -->
                <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-2xl mb-6">
                    @if($stream->embed_code)
                        <div class="w-full h-full">
                            {!! $stream->embed_code !!}
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                </div>
                                <p class="text-xl font-semibold">Live Stream Active</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Stream Info -->
                <div class="text-center">
                    @if($stream->description)
                        <p class="text-gray-300 mb-6">{{ $stream->description }}</p>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                        @if($stream->estimated_viewers > 0)
                            <div class="flex items-center text-gray-300">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ number_format($stream->estimated_viewers) }} watching
                            </div>
                        @endif

                        <a href="{{ route('live-streams.show', $stream->slug) }}"
                           class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                            Watch Full Screen
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

        @elseif($upcomingStream)
            <!-- Upcoming Stream -->
            <div class="text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Next Live Stream</h2>
                <div class="max-w-2xl mx-auto bg-gray-800 rounded-lg p-8">
                    <h3 class="text-2xl font-bold mb-4">{{ $upcomingStream->title }}</h3>

                    @if($upcomingStream->description)
                        <p class="text-gray-300 mb-6">{{ $upcomingStream->description }}</p>
                    @endif

                    <div class="mb-6">
                        <div class="text-sm text-gray-400 mb-2">Starting in:</div>
                        <div class="text-3xl font-bold text-yellow-400">{{ $upcomingStream->time_until_start }}</div>
                    </div>

                    <div class="text-sm text-gray-400 mb-6">
                        {{ $upcomingStream->scheduled_start->format('l, F j, Y \a	 g:i A') }}
                    </div>

                    <a href="{{ route('live-streams.show', $upcomingStream->slug) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Set Reminder
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Link to All Streams -->
        <div class="text-center mt-8">
            <a href="{{ route('live-streams.index') }}"
               class="text-gray-300 hover:text-white transition-colors inline-flex items-center">
                View All Streams
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif
