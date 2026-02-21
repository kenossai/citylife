<x-app-layout>
@section('title', $speaker->name . ' - Bible School International')
@section('description', $speaker->bio ?? 'View teaching sessions from ' . $speaker->name)

<style>
    body { background: #f5f5f5; margin: 0; padding: 0; }
    .dg-video-hero { background: #000; padding: 40px 20px 20px; margin: 0; }
    .dg-video-wrapper { max-width: 1200px; margin: 0 auto; position: relative; }
    .dg-video-wrapper iframe { width: 100%; height: 600px; display: block; border: 0; }

    .dg-locked-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 600px;
        background: linear-gradient(160deg, #0d1b3e 0%, #1a3a6e 50%, #0d2a52 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        overflow: hidden;
    }
    /* Decorative background circles */
    .dg-locked-overlay::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
        top: -150px;
        right: -150px;
        pointer-events: none;
    }
    .dg-locked-overlay::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
        bottom: -100px;
        left: -80px;
        pointer-events: none;
    }
    .dg-lock-card {
        position: relative;
        z-index: 2;
        background: rgba(255,255,255,0.07);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 44px 40px 36px;
        width: 100%;
        max-width: 480px;
        margin: 0 20px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.5);
        text-align: center;
        color: #fff;
    }
    .dg-lock-card__badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,193,7,0.15);
        border: 1px solid rgba(255,193,7,0.4);
        color: #ffc107;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 20px;
        margin-bottom: 24px;
    }
    .dg-lock-card__icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,193,7,0.2), rgba(255,193,7,0.05));
        border: 1px solid rgba(255,193,7,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .dg-lock-card__icon-wrap i { font-size: 28px; color: #ffc107; }
    .dg-lock-card__title { font-size: 24px; font-weight: 700; margin-bottom: 10px; line-height: 1.2; }
    .dg-lock-card__sub { font-size: 14px; color: rgba(255,255,255,0.65); margin-bottom: 28px; line-height: 1.6; }
    .dg-lock-card__sub strong { color: rgba(255,255,255,0.9); }

    .dg-lock-input {
        width: 100%;
        box-sizing: border-box;
        padding: 13px 16px;
        font-size: 15px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 10px;
        color: #fff;
        margin-bottom: 12px;
        transition: border-color 0.2s, background 0.2s;
    }
    .dg-lock-input::placeholder { color: rgba(255,255,255,0.4); }
    .dg-lock-input:focus {
        outline: none;
        border-color: rgba(255,193,7,0.6);
        background: rgba(255,255,255,0.11);
    }
    .dg-lock-input--code {
        text-transform: uppercase;
        letter-spacing: 5px;
        font-size: 20px;
        font-family: 'Courier New', Courier, monospace;
        text-align: center;
        font-weight: 700;
    }

    .dg-consent-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
        text-align: left;
    }
    .dg-consent-row input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin-top: 2px;
        flex-shrink: 0;
        accent-color: #ffc107;
        cursor: pointer;
    }
    .dg-consent-row label {
        font-size: 12px;
        color: rgba(255,255,255,0.65);
        line-height: 1.5;
        cursor: pointer;
    }
    .dg-consent-row label a { color: #ffc107; text-decoration: underline; }

    .dg-lock-btn {
        width: 100%;
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        border: none;
        background: linear-gradient(135deg, #ffc107, #ffab00);
        color: #000;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 20px rgba(255,193,7,0.35);
        margin-top: 4px;
    }
    .dg-lock-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(255,193,7,0.5); }
    .dg-lock-btn:active { transform: translateY(0); }
    .dg-lock-btn i { margin-right: 6px; }

    .dg-lock-alert {
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 13px;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dg-lock-alert.error {
        background: rgba(244, 67, 54, 0.18);
        border: 1px solid rgba(244,67,54,0.35);
        color: #ffcdd2;
    }
    .dg-lock-alert.success {
        background: rgba(76, 175, 80, 0.18);
        border: 1px solid rgba(76,175,80,0.35);
        color: #c8e6c9;
    }
    .dg-lock-footer {
        margin-top: 20px;
        font-size: 12px;
        color: rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .dg-lock-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0 16px;
        color: rgba(255,255,255,0.25);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .dg-lock-divider::before, .dg-lock-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.1);
    }
    .dg-resend-link {
        font-size: 13px;
        color: rgba(255,255,255,0.5);
    }
    .dg-resend-link a { color: #ffc107; text-decoration: none; font-weight: 600; }
    .dg-resend-link a:hover { text-decoration: underline; }

    .dg-main { background: #fff; }
    .dg-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

    .dg-controls { display: flex; justify-content: center; gap: 12px; padding: 20px 0 0; flex-wrap: wrap; }
    .dg-btn { padding: 10px 20px; font-size: 14px; font-weight: 500; border: 1px solid #555; background: #333; color: #fff; border-radius: 3px; text-decoration: none; transition: all 0.2s; cursor: pointer; }
    .dg-btn:hover { background: #444; color: #fff; }
    .dg-btn.active { background: #555; border-color: #777; }

    .dg-audio-player { display: none; max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
    .dg-audio-player.active { display: block; }
    .dg-audio-player__title { color: #ccc; font-size: 16px; text-align: center; margin-bottom: 40px; }
    .dg-audio-player audio { width: 100%; max-width: 800px; display: block; margin: 0 auto; }

    .dg-date { text-align: center; font-size: 13px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; padding: 40px 0 20px; }

    .dg-title { text-align: center; font-size: 56px; font-weight: 700; color: #1a1a1a; line-height: 1.1; padding: 0 20px 40px; margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }

    .dg-content { max-width: 820px; margin: 0 auto; padding: 0 20px 80px; }
    .dg-content p { font-size: 18px; line-height: 1.75; color: #333; margin-bottom: 24px; }
    .dg-content h2 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin-top: 56px; margin-bottom: 20px; line-height: 1.2; }
    .dg-content h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-top: 40px; margin-bottom: 16px; line-height: 1.3; }
    .dg-content h4 { font-size: 20px; font-weight: 600; color: #1a1a1a; margin-top: 32px; margin-bottom: 12px; }

    .dg-intro { font-size: 22px; line-height: 1.6; color: #555; margin-bottom: 40px; font-weight: 400; }

    .dg-meta { max-width: 720px; margin: 0 auto; padding: 0 20px 30px; display: flex; gap: 20px; align-items: center; font-size: 15px; color: #666; flex-wrap: wrap; justify-content: center; }
    .dg-meta__item { display: flex; align-items: center; gap: 8px; }
    .dg-meta__label { color: #999; }
    .dg-meta__value { color: #333; font-weight: 500; }

    .dg-resources-list { max-width: 820px; margin: 0 auto; padding: 40px 20px; }
    .dg-resources-section { margin-bottom: 50px; }
    .dg-resources-section h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 20px; }
    .dg-resource-item { background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 15px; transition: all 0.3s; }
    .dg-resource-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .dg-resource-title { font-size: 18px; font-weight: 600; color: #1a1a1a; margin-bottom: 8px; }
    .dg-resource-meta { font-size: 14px; color: #666; margin-bottom: 12px; }

    @media (max-width: 768px) {
        .dg-video-wrapper iframe, .dg-locked-overlay { height: auto; min-height: 520px; position: relative; }
        .dg-video-hero { padding: 20px 10px 0; }
        .dg-title { font-size: 32px; padding-bottom: 30px; }
        .dg-content p { font-size: 16px; }
        .dg-controls { gap: 8px; padding: 16px 0; }
        .dg-btn { font-size: 13px; padding: 8px 16px; }
        .dg-lock-card { padding: 32px 24px 28px; border-radius: 16px; }
        .dg-lock-card__title { font-size: 20px; }
    }
</style>

@php
    // Check whether the speaker has ANY media at all (drives the hero wrapper)
    $hasAnyMedia = false;
    foreach($speaker->events as $event) {
        if ($event->videos->count() > 0 || $event->audios->count() > 0) {
            $hasAnyMedia = true;
            break;
        }
    }

    // First video/audio for the hero player – only from years the user has access to
    $firstVideo = null;
    $firstAudio = null;
    foreach($speaker->events as $event) {
        if (!in_array($event->year, $accessibleYears->all())) continue;
        if (!$firstVideo && $event->videos->count() > 0) {
            $firstVideo = $event->videos->first();
        }
        if (!$firstAudio && $event->audios->count() > 0) {
            $firstAudio = $event->audios->first();
        }
        if ($firstVideo && $firstAudio) break;
    }
@endphp

<div>
    <!-- Video/Audio Hero -->
    @if($hasAnyMedia)
        <div class="dg-video-hero">
            @if($hasAccess && ($firstVideo || $firstAudio))
                <!-- Video Player (Unlocked) -->
                @if($firstVideo)
                    <div class="dg-video-wrapper" id="videoPlayer">
                        @if(str_contains($firstVideo->video_url, 'youtube.com') || str_contains($firstVideo->video_url, 'youtu.be'))
                            @php
                                $videoId = '';
                                if (str_contains($firstVideo->video_url, 'youtube.com/watch?v=')) {
                                    parse_str(parse_url($firstVideo->video_url, PHP_URL_QUERY), $vars);
                                    $videoId = $vars['v'] ?? '';
                                } elseif (str_contains($firstVideo->video_url, 'youtu.be/')) {
                                    $videoId = basename(parse_url($firstVideo->video_url, PHP_URL_PATH));
                                }
                            @endphp
                            @if($videoId)
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                            @endif
                        @elseif(str_contains($firstVideo->video_url, 'vimeo.com'))
                            @php
                                $vimeoId = basename(parse_url($firstVideo->video_url, PHP_URL_PATH));
                            @endphp
                            <iframe src="https://player.vimeo.com/video/{{ $vimeoId }}" allowfullscreen></iframe>
                        @else
                            <video src="{{ $firstVideo->video_url }}" controls style="width:100%;height:600px;"></video>
                        @endif
                    </div>
                @endif

                <!-- Audio Player (Unlocked) -->
                @if($firstAudio)
                    <div class="dg-audio-player" id="audioPlayer" {!! $firstVideo ? '' : 'class="active"' !!}>
                        <div class="dg-audio-player__title">{{ $firstAudio->title }}</div>
                        <audio controls controlsList="nodownload">
                            <source src="{{ $firstAudio->audio_url }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                @endif

                <!-- Controls inside black background -->
                <div class="dg-controls">
                    @if($firstVideo)
                        <button onclick="switchToVideo()" class="dg-btn active" id="videoBtn">Video</button>
                    @endif
                    @if($firstAudio)
                        <button onclick="switchToAudio()" class="dg-btn {{ !$firstVideo ? 'active' : '' }}" id="audioBtn">Audio</button>
                    @endif
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
            @else
                <!-- Locked State – 2-step: email → BS###### code -->
                <div class="dg-video-wrapper">
                    <div style="width:100%;height:600px;background:#0d1b3e;"></div>
                    <div class="dg-locked-overlay">
                        <div class="dg-lock-card">

                            @if(session('bsi_step') === 'verify')
                                {{-- ── Step 2: Enter the BS###### code ─────────────────── --}}
                                <div class="dg-lock-card__badge">
                                    <i class="fas fa-paper-plane"></i> Code Sent
                                </div>
                                <div class="dg-lock-card__icon-wrap">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <h2 class="dg-lock-card__title">Check Your Inbox</h2>
                                <p class="dg-lock-card__sub">
                                    We emailed your access code to<br>
                                    <strong>{{ session('bsi_pending_email') }}</strong><br>
                                    @if(session('bsi_pending_year'))
                                        <span style="color: rgba(255,193,7,0.9); font-size: 13px;">
                                            <i class="fas fa-calendar-alt"></i> Unlocking {{ session('bsi_pending_year') }} resources
                                        </span>
                                    @endif
                                </p>

                                <form action="{{ route('bible-school-international.verify-email-code', $speaker->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ session('bsi_pending_email') }}">
                                    <input type="hidden" name="year" value="{{ session('bsi_pending_year', $requestedYear ?? '') }}">

                                    @if(session('error'))
                                        <div class="dg-lock-alert error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="dg-lock-alert success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <input type="text"
                                           class="dg-lock-input dg-lock-input--code"
                                           name="otp"
                                           placeholder="BS000000"
                                           maxlength="10"
                                           required
                                           autocomplete="one-time-code"
                                           autofocus>

                                    <button type="submit" class="dg-lock-btn">
                                        <i class="fas fa-unlock-alt"></i> Unlock Resources
                                    </button>
                                </form>

                                <div class="dg-lock-divider">didn't receive the code?</div>
                                <p class="dg-resend-link">
                                    Check your spam folder or
                                    <a href="{{ route('bible-school-international.speaker', $speaker->id) . '?year=' . session('bsi_pending_year', $requestedYear ?? '') }}">try a different email</a>
                                </p>

                            @else
                                {{-- ── Step 1: Enter email ──────────────────────────────── --}}
                                <div class="dg-lock-card__badge">
                                    <i class="fas fa-graduation-cap"></i> Bible School International
                                </div>
                                <div class="dg-lock-card__icon-wrap">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h2 class="dg-lock-card__title">Access Teaching Resources</h2>
                                <p class="dg-lock-card__sub">
                                    Enter your email to receive a one-time code and unlock all of
                                    {{ $speaker->name }}'s
                                    @if($requestedYear) <strong style="color: rgba(255,193,7,0.9);">{{ $requestedYear }}</strong> @endif
                                    sessions.
                                </p>

                                <form action="{{ route('bible-school-international.send-speaker-code', $speaker->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="year" value="{{ $requestedYear ?? $speaker->events->max('year') }}">
                                    @if(session('error'))
                                        <div class="dg-lock-alert error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    @error('consent')
                                        <div class="dg-lock-alert error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <input type="email"
                                           class="dg-lock-input"
                                           name="email"
                                           placeholder="your@email.com"
                                           required
                                           autocomplete="email"
                                           value="{{ old('email') }}">

                                    <div class="dg-consent-row">
                                        <input type="checkbox" name="consent" id="bsi_consent" value="1" {{ old('consent') ? 'checked' : '' }}>
                                        <label for="bsi_consent">
                                            I agree to my email being stored to verify access to Bible School International resources.
                                            We will not use it for any other purpose.
                                        </label>
                                    </div>

                                    <button type="submit" class="dg-lock-btn">
                                        <i class="fas fa-paper-plane"></i> Send Me the Code
                                    </button>
                                </form>
                            @endif

                            <div class="dg-lock-footer">
                                <i class="fas fa-shield-alt"></i>
                                Secure &bull; One-time code &bull; Expires in 10 minutes
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="dg-main">
        <!-- Speaker Info -->
        <div class="dg-date">
            {{ strtoupper($speaker->title ?? 'SPEAKER') }}
            @if($speaker->organization)
                | {{ strtoupper($speaker->organization) }}
            @endif
        </div>

        <!-- Title -->
        <h1 class="dg-title">{{ $speaker->name }}</h1>

        <!-- Meta Info -->
        <div class="dg-meta">
            @php
                $totalVideos = 0;
                $totalAudios = 0;
                foreach($speaker->events as $event) {
                    $totalVideos += $event->videos->count();
                    $totalAudios += $event->audios->count();
                }
            @endphp
            <div class="dg-meta__item">
                <span class="dg-meta__label">Sessions:</span>
                <span class="dg-meta__value">{{ $speaker->events->count() }}</span>
            </div>
            <div class="dg-meta__item">
                <span class="dg-meta__label">Videos:</span>
                <span class="dg-meta__value">{{ $totalVideos }}</span>
            </div>
            <div class="dg-meta__item">
                <span class="dg-meta__label">Audios:</span>
                <span class="dg-meta__value">{{ $totalAudios }}</span>
            </div>
        </div>

        <!-- Content -->
        <article class="dg-content">
            @if($speaker->bio)
                <p class="dg-intro">{{ $speaker->bio }}</p>
            @endif

            @if($hasAccess)
                <!-- Speaker Photo -->
                <div style="text-align: center; margin: 40px 0;">
                    @if($speaker->photo)
                        <img src="{{ Storage::url($speaker->photo) }}" alt="{{ $speaker->name }}" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    @endif
                </div>
            @endif
        </article>

        @if($hasAccess && $speaker->events->count() > 0)
            <!-- Teaching Sessions Resources List (accessible years only) -->
            <div class="dg-resources-list">
                @foreach($speaker->events->filter(fn($e) => $accessibleYears->contains($e->year)) as $event)
                    <div class="dg-resources-section">
                        <h3>
                            {{ $event->title }}
                            <span style="color: #2c5aa0; font-size: 20px;">({{ $event->year }})</span>
                        </h3>

                        @if($event->description)
                            <p style="color: #666; margin-bottom: 25px;">{{ Str::limit(strip_tags($event->description), 200) }}</p>
                        @endif

                        <!-- Videos -->
                        @foreach($event->videos as $video)
                            <div class="dg-resource-item">
                                <div class="dg-resource-title">
                                    <i class="fas fa-video" style="color: #2c5aa0; margin-right: 8px;"></i>
                                    {{ $video->title }}
                                </div>
                                <div class="dg-resource-meta">
                                    @if($video->duration)
                                        <i class="far fa-clock"></i> {{ $video->formatted_duration }}
                                    @endif
                                    @if($video->description)
                                        • {{ Str::limit($video->description, 100) }}
                                    @endif
                                </div>
                                <div class="dg-controls" style="padding: 0; justify-content: flex-start;">
                                    <button onclick="loadVideoInHero('{{ $video->id }}', '{{ $video->video_url }}', '{{ $video->title }}')" class="dg-btn">
                                        <i class="fas fa-play"></i> Watch Now
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Audios -->
                        @foreach($event->audios as $audio)
                            <div class="dg-resource-item">
                                <div class="dg-resource-title">
                                    <i class="fas fa-music" style="color: #17a2b8; margin-right: 8px;"></i>
                                    {{ $audio->title }}
                                </div>
                                <div class="dg-resource-meta">
                                    @if($audio->duration)
                                        <i class="far fa-clock"></i> {{ $audio->formatted_duration }}
                                    @endif
                                    @if($audio->description)
                                        • {{ Str::limit($audio->description, 100) }}
                                    @endif
                                </div>
                                <div class="dg-controls" style="padding: 0; justify-content: flex-start;">
                                    <button onclick="loadAudioInHero('{{ $audio->id }}', '{{ $audio->audio_url }}', '{{ $audio->title }}')" class="dg-btn">
                                        <i class="fas fa-play"></i> Listen Now
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <script>
                function loadVideoInHero(videoId, videoUrl, videoTitle) {
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Get video player
                    const videoPlayer = document.getElementById('videoPlayer');
                    const audioPlayer = document.getElementById('audioPlayer');

                    if (videoPlayer) {
                        // Extract video ID based on URL type
                        let embedUrl = '';
                        if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
                            let ytVideoId = '';
                            if (videoUrl.includes('youtube.com/watch?v=')) {
                                ytVideoId = videoUrl.split('v=')[1].split('&')[0];
                            } else if (videoUrl.includes('youtu.be/')) {
                                ytVideoId = videoUrl.split('youtu.be/')[1].split('?')[0];
                            }
                            embedUrl = `https://www.youtube.com/embed/${ytVideoId}`;
                        } else if (videoUrl.includes('vimeo.com')) {
                            const vimeoId = videoUrl.split('vimeo.com/')[1].split('?')[0];
                            embedUrl = `https://player.vimeo.com/video/${vimeoId}`;
                        }

                        if (embedUrl) {
                            videoPlayer.innerHTML = `<iframe src="${embedUrl}" allowfullscreen style="width:100%;height:600px;border:0;"></iframe>`;
                        } else {
                            videoPlayer.innerHTML = `<video src="${videoUrl}" controls style="width:100%;height:600px;"></video>`;
                        }

                        // Show video, hide audio
                        videoPlayer.style.display = 'block';
                        audioPlayer.classList.remove('active');
                        document.getElementById('videoBtn').classList.add('active');
                        document.getElementById('audioBtn').classList.remove('active');
                    }
                }

                function loadAudioInHero(audioId, audioUrl, audioTitle) {
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Get audio player
                    const audioPlayer = document.getElementById('audioPlayer');
                    const videoPlayer = document.getElementById('videoPlayer');

                    if (audioPlayer) {
                        audioPlayer.querySelector('.dg-audio-player__title').textContent = audioTitle;
                        audioPlayer.querySelector('audio source').src = audioUrl;
                        audioPlayer.querySelector('audio').load();

                        // Show audio, hide video
                        videoPlayer.style.display = 'none';
                        audioPlayer.classList.add('active');
                        document.getElementById('videoBtn').classList.remove('active');
                        document.getElementById('audioBtn').classList.add('active');

                        // Auto-play
                        audioPlayer.querySelector('audio').play();
                    }
                }
            </script>
        @elseif(!$hasAccess)
            <div class="dg-content" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-envelope" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 15px;">Enter Your Email Above</h3>
                <p style="color: #999;">We'll send you a one-time code to unlock all teaching sessions and resources from {{ $speaker->name }}</p>
                <div style="margin-top: 30px;">
                    <a href="{{ route('bible-school-international.resources') }}" class="dg-btn">
                        <i class="fas fa-arrow-left"></i> Back to Resources
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

</x-app-layout>
