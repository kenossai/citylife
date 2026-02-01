<x-app-layout>
@section('title', $series->title . ' - Teaching Series')
@section('description', $series->summary ?? $series->excerpt)

<style>
    body { background: #f5f5f5; margin: 0; padding: 0; }
    .dg-video-hero { background: #000; padding: 40px 20px 20px; margin: 0; }
    .dg-video-wrapper { max-width: 1200px; margin: 0 auto; }
    .dg-video-wrapper iframe { width: 100%; height: 600px; display: block; border: 0; }

    .dg-main { background: #fff; }
    .dg-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    .dg-controls { display: flex; justify-content: center; gap: 12px; padding: 20px 0 0; }
    .dg-btn { padding: 10px 20px; font-size: 14px; font-weight: 500; border: 1px solid #555; background: #333; color: #fff; border-radius: 3px; text-decoration: none; transition: all 0.2s; cursor: pointer; }
    .dg-btn:hover { background: #444; }
    .dg-btn.active { background: #555; border-color: #777; }

    .dg-audio-player { display: none; max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
    .dg-audio-player.active { display: block; }
    .dg-audio-player__title { color: #ccc; font-size: 16px; text-align: center; margin-bottom: 40px; }
    .dg-audio-player audio { width: 100%; max-width: 800px; display: block; margin: 0 auto; }

    .dg-date { text-align: center; font-size: 13px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding: 40px 0 20px; }

    .dg-title { text-align: center; font-size: 56px; font-weight: 700; color: #1a1a1a; line-height: 1.1; padding: 0 20px 60px; margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }

    .dg-content { max-width: 820px; margin: 0 auto; padding: 0 20px 80px; }
    .dg-content p { font-size: 18px; line-height: 1.75; color: #333; margin-bottom: 24px; }
    .dg-content h2 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin-top: 56px; margin-bottom: 20px; line-height: 1.2; }
    .dg-content h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-top: 40px; margin-bottom: 16px; line-height: 1.3; }
    .dg-content h4 { font-size: 20px; font-weight: 600; color: #1a1a1a; margin-top: 32px; margin-bottom: 12px; }
    .dg-content blockquote { border-left: 4px solid #ccc; padding-left: 24px; margin: 32px 0; font-style: italic; color: #555; font-size: 18px; }
    .dg-content ul, .dg-content ol { margin: 24px 0; padding-left: 32px; }
    .dg-content li { margin-bottom: 12px; line-height: 1.7; }

    .dg-intro { font-size: 22px; line-height: 1.6; color: #555; margin-bottom: 40px; font-weight: 400; }

    .dg-meta { max-width: 720px; margin: 0 auto; padding: 0 20px 30px; display: flex; gap: 20px; align-items: center; font-size: 15px; color: #666; flex-wrap: wrap; }
    .dg-meta__label { color: #999; }
    .dg-meta__value { color: #333; font-weight: 500; }
    .dg-meta__link { color: #4a9eff; text-decoration: none; font-weight: 500; }
    .dg-meta__link:hover { text-decoration: underline; }

    .dg-scripture { background: #f0f7ff; border-left: 4px solid #2c5aa0; padding: 24px; margin: 32px 0; border-radius: 3px; }
    .dg-scripture h4 { margin: 0 0 16px 0; color: #2c5aa0; font-size: 18px; font-weight: 600; }
    .dg-scripture p { font-size: 16px; color: #333; margin: 0; line-height: 1.6; }

    .dg-notes { background: #fafafa; border: 1px solid #e0e0e0; border-radius: 3px; padding: 28px; margin: 40px 0; }
    .dg-notes h4 { margin: 0 0 20px 0; color: #2c5aa0; font-size: 18px; font-weight: 600; }
    .dg-notes__content { font-size: 16px; line-height: 1.7; color: #333; }

    .dg-author { border-top: 1px solid #e0e0e0; padding-top: 40px; margin-top: 60px; }
    .dg-author__name { font-size: 19px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px; }
    .dg-author__bio { font-size: 15px; color: #666; line-height: 1.6; }

    .dg-share { border-top: 1px solid #e0e0e0; padding: 40px 0; margin-top: 60px; }
    .dg-share__inner { max-width: 720px; margin: 0 auto; padding: 0 20px; display: flex; gap: 16px; align-items: center; }
    .dg-share__label { font-size: 15px; font-weight: 600; color: #333; }
    .dg-share__links { display: flex; gap: 10px; }
    .dg-share__link { width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border: 1px solid #ddd; border-radius: 50%; color: #666; text-decoration: none; font-size: 15px; transition: background 0.2s; }
    .dg-share__link:hover { background: #e5e5e5; }

    .dg-upcoming { background: linear-gradient(135deg, #2c5aa0 0%, #1a3560 100%); height: 600px; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #fff; text-align: center; }
    .dg-upcoming__icon { font-size: 72px; margin-bottom: 28px; opacity: 0.9; }
    .dg-upcoming__title { font-size: 36px; font-weight: 600; margin-bottom: 16px; }
    .dg-upcoming__date { font-size: 20px; opacity: 0.9; }

    .dg-badge { display: inline-block; padding: 6px 12px; background: #ffc107; color: #000; border-radius: 3px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-left: 16px; }

    @media (max-width: 768px) {
        .dg-video-wrapper iframe { height: 300px; }
        .dg-video-hero { padding: 20px 10px 0; }
        .dg-title { font-size: 32px; padding-bottom: 40px; }
        .dg-content p { font-size: 16px; }
        .dg-controls { gap: 8px; padding: 16px 0; }
        .dg-btn { font-size: 13px; padding: 8px 16px; }
    }
</style>

<div>
    <!-- Video/Audio Hero -->
    @if(($series->video_url || $series->audio_url) && !$series->is_upcoming)
        <div class="dg-video-hero">
            <!-- Video Player -->
            @if($series->video_url)
                <div class="dg-video-wrapper" id="videoPlayer">
                    @if(str_contains($series->video_url, 'youtube.com') || str_contains($series->video_url, 'youtu.be'))
                        @php
                            $videoId = '';
                            if (str_contains($series->video_url, 'youtube.com/watch?v=')) {
                                parse_str(parse_url($series->video_url, PHP_URL_QUERY), $vars);
                                $videoId = $vars['v'] ?? '';
                            } elseif (str_contains($series->video_url, 'youtu.be/')) {
                                $videoId = basename(parse_url($series->video_url, PHP_URL_PATH));
                            }
                        @endphp
                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                        @endif
                    @else
                        {{-- <video src="{{ $series->video_url }}" controls style="width:100%;height:600px;"></video> --}}
                    @endif
                </div>
            @endif

            <!-- Audio Player -->
            @if($series->audio_url)
                <div class="dg-audio-player" id="audioPlayer">
                    <div class="dg-audio-player__title">{{ $series->title }}</div>
                    <audio controls controlsList="nodownload">
                        <source src="{{ $series->audio_url }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            @endif

            <!-- Controls inside black background -->
            <div class="dg-controls">
                @if($series->video_url)
                    <button onclick="switchToVideo()" class="dg-btn active" id="videoBtn">Video</button>
                @endif
                @if($series->audio_url)
                    <button onclick="switchToAudio()" class="dg-btn" id="audioBtn">Audio</button>
                @endif
                @if($series->sermon_notes)
                    <a href="{{ $series->sermon_notes_url }}" target="_blank" class="dg-btn">Download</a>
                @endif
            </div>
        </div>

        <script>
            function switchToVideo() {
                document.getElementById('videoPlayer').style.display = 'block';
                document.getElementById('audioPlayer').classList.remove('active');
                document.getElementById('videoBtn').classList.add('active');
                document.getElementById('audioBtn').classList.remove('active');
            }

            function switchToAudio() {
                document.getElementById('videoPlayer').style.display = 'none';
                document.getElementById('audioPlayer').classList.add('active');
                document.getElementById('videoBtn').classList.remove('active');
                document.getElementById('audioBtn').classList.add('active');
            }
        </script>
    @endif

            </div>
        </div>

        <!-- Date -->
        <div class="dg-date">
            @if($series->series_date)
                {{ strtoupper($series->series_date->format('F j, Y')) }}
            @endif
        </div>

        <!-- Title -->
        <h1 class="dg-title">
            {{ $series->title }}
            @if($series->is_upcoming)<span class="dg-badge">Upcoming</span>@endif
        </h1>

        <!-- Meta Info -->
        @if($series->pastor || ($series->tags && count($series->tags) > 0))
            <div class="dg-meta">
                @if($series->pastor)
                    <div>
                        <span class="dg-meta__label">Message by</span>
                        <span class="dg-meta__value">{{ $series->pastor }}</span>
                    </div>
                @endif
                @if($series->tags && count($series->tags) > 0)
                    <div>
                        <span class="dg-meta__label">Tags:</span>
                        @foreach(array_slice($series->tags, 0, 8) as $tag)
                            <a href="{{ route('teaching-series.index', ['search' => $tag]) }}" class="dg-meta__link">{{ $tag }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Content -->
        <article class="dg-content">
            @if($series->summary)
                <p class="dg-intro">{{ $series->summary }}</p>
            @endif

            @if($series->description)
                {!! $series->description !!}
            @endif

            @if($series->scripture_references)
                <div class="dg-scripture">
                    <h4>Scripture References</h4>
                    <p>{{ $series->scripture_references }}</p>
                </div>
            @endif

            @if($series->sermon_notes_content)
                <div class="dg-notes">
                    <h4>Sermon Notes</h4>
                    <div class="dg-notes__content">
                        @if($series->sermon_notes_content_type === 'markdown')
                            {!! \Illuminate\Support\Str::markdown($series->sermon_notes_content) !!}
                        @elseif($series->sermon_notes_content_type === 'plain_text')
                            <pre style="white-space:pre-wrap;font-family:inherit;margin:0;">{!! nl2br(e($series->sermon_notes_content)) !!}</pre>
                        @else
                            {!! $series->sermon_notes_content !!}
                        @endif
                    </div>
                    @if($series->sermon_notes)
                        <div style="margin-top:20px;">
                            <a href="{{ $series->sermon_notes_url }}" target="_blank" class="dg-btn">Download PDF</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($series->pastor)
                <div class="dg-author">
                    <div class="dg-author__name">{{ $series->pastor }}</div>
                    {{-- <div class="dg-author__bio">Pastor at City Life International Church</div> --}}
                </div>
            @endif
        </article>

        <!-- Share -->
        <div class="dg-share">
            <div class="dg-share__inner">
                <span class="dg-share__label">Share:</span>
                <div class="dg-share__links">
                    <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="dg-share__link"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($series->title) }}" target="_blank" class="dg-share__link"><i class="fab fa-twitter"></i></a>
                    <a href="https://wa.me/?text={{ urlencode($series->title) }}%20{{ urlencode(request()->fullUrl()) }}" target="_blank" class="dg-share__link"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:?subject={{ urlencode($series->title) }}&body={{ urlencode(request()->fullUrl()) }}" class="dg-share__link"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
