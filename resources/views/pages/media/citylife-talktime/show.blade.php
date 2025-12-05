<x-app-layout>
@section('title', $talkTime->title . ' - CityLife TalkTime')
@section('description', $talkTime->description ?? Str::limit($talkTime->description, 160))

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ $talkTime->image ? asset('media/' . $talkTime->image) : asset('assets/images/backgrounds/worship-banner-1.jpg') }}');"></div>
    <div class="container">
        <h2 class="text-white">{{ $talkTime->title }}</h2>
        <h2 class="page-header__title">CityLife TalkTime</h2>
        <ul class="citylife-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('citylife-talktime.index') }}">CityLife TalkTime</a></li>
            <li><span>{{ $talkTime->title }}</span></li>
        </ul>
    </div>
</section>

<section class="blog-page section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="blog-details">
                    <div class="blog-card blog-card-four wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms">
                        @if($talkTime->video_url)
                            <!-- Video Player in Card Header -->
                            <div class="blog-card__image position-relative" style="z-index: 10;">
                                <style>
                                    .blog-card__image::before,
                                    .blog-card__image::after {
                                        display: none !important;
                                    }
                                </style>
                                @if(str_contains($talkTime->video_url, 'youtube.com') || str_contains($talkTime->video_url, 'youtu.be'))
                                    @php
                                        $videoId = '';
                                        if (str_contains($talkTime->video_url, 'youtube.com/watch?v=')) {
                                            parse_str(parse_url($talkTime->video_url, PHP_URL_QUERY), $vars);
                                            $videoId = $vars['v'] ?? '';
                                        } elseif (str_contains($talkTime->video_url, 'youtu.be/')) {
                                            $videoId = basename(parse_url($talkTime->video_url, PHP_URL_PATH));
                                        }
                                    @endphp
                                    @if($videoId)
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                    title="{{ $talkTime->title }}"
                                                    allowfullscreen
                                                    class="rounded"></iframe>
                                        </div>
                                    @else
                                        <div class="position-relative">
                                            <img src="{{ $talkTime->image ? asset('media/' . $talkTime->image) : asset('assets/images/defaults/talktime-default.jpg') }}" alt="{{ $talkTime->title }}">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <a href="{{ $talkTime->video_url }}" target="_blank" class="citylife-btn citylife-btn--base">
                                                    <span class="citylife-btn__icon-box">
                                                        <span class="citylife-btn__icon-box__inner"><span class="icon-play"></span></span>
                                                    </span>
                                                    <span class="citylife-btn__text">Watch Video</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="position-relative">
                                        <img src="{{ $talkTime->image ? asset('media/' . $talkTime->image) : asset('assets/images/defaults/talktime-default.jpg') }}" alt="{{ $talkTime->title }}">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <a href="{{ $talkTime->video_url }}" target="_blank" class="citylife-btn citylife-btn--base">
                                                <span class="citylife-btn__icon-box">
                                                    <span class="citylife-btn__icon-box__inner"><span class="icon-play"></span></span>
                                                </span>
                                                <span class="citylife-btn__text">Watch Video</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($talkTime->host)
                                    <div class="blog-details__hall">
                                        <span>Host: {{ $talkTime->host }}</span>
                                    </div>
                                @endif
                                @if($talkTime->episode_date)
                                    <div class="blog-card__date">
                                        <span>{{ $talkTime->episode_date->format('d') }}</span>
                                        {{ $talkTime->episode_date->format('M') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Fallback to Image if no video -->
                            <div class="blog-card__image">
                                <img src="{{ $talkTime->image ? asset('media/' . $talkTime->image) : asset('assets/images/defaults/talktime-default.jpg') }}" alt="{{ $talkTime->title }}">
                                @if($talkTime->host)
                                    <div class="blog-details__hall">
                                        <span>Host:</span>
                                        <span>{{ $talkTime->host }}</span>
                                    </div>
                                @endif
                                @if($talkTime->episode_date)
                                    <div class="blog-card__date">
                                        <span>{{ $talkTime->episode_date->format('d') }}</span>
                                        {{ $talkTime->episode_date->format('M') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="blog-card-four__content">
                            <ul class="list-unstyled blog-card-four__meta">
                                @if($talkTime->host)
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="icon-user"></span>
                                        Host: {{ $talkTime->host }}
                                    </a>
                                </li>
                                @endif
                                @if($talkTime->guest)
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="icon-users"></span>
                                        Guest: {{ $talkTime->guest }}
                                    </a>
                                </li>
                                @endif
                                @if($talkTime->audio_url)
                                <li>
                                    <a href="{{ $talkTime->audio_url }}" target="_blank">
                                        <i class="fa-solid fa-headphones"></i>
                                        Listen to Audio
                                    </a>
                                </li>
                                @endif
                                @if($talkTime->duration)
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="icon-clock"></span>
                                        Duration: {{ $talkTime->duration }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                            <h3 class="blog-card__title">{{ $talkTime->title }}</h3>

                            @if($talkTime->description)
                                <div class="blog-card-four__text blog-card-four__text--two">
                                    {!! $talkTime->description !!}
                                </div>
                            @endif

                            @if($talkTime->key_topics)
                                <div class="blog-details__inner">
                                    <div class="blog-details__inner__content">
                                        <h4>Key Topics Discussed</h4>
                                        <p class="blog-details__inner__text">{{ $talkTime->key_topics }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="blog-details__meta">
                        @if($talkTime->tags && count($talkTime->tags) > 0)
                            <div class="blog-details__tags">
                                <h4 class="blog-details__meta__title">Tags:</h4>
                                <div class="blog-details__tags__box">
                                    @foreach($talkTime->tags as $tag)
                                        <a href="{{ route('citylife-talktime.index', ['search' => $tag]) }}">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="blog-details__social">
                            <h4 class="blog-details__meta__title">Share:</h4>
                            <div class="social-link">
                                <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                    <span class="sr-only">Facebook</span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($talkTime->title) }}" target="_blank">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                    <span class="sr-only">Twitter</span>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($talkTime->title) }}%20{{ urlencode(request()->fullUrl()) }}" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    <span class="sr-only">WhatsApp</span>
                                </a>
                                <a href="mailto:?subject={{ urlencode($talkTime->title) }}&body={{ urlencode(request()->fullUrl()) }}">
                                    <i class="fab fa-envelope"></i>
                                    <span class="sr-only">Email</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    <aside class="widget-area">
                        <div class="sidebar__form sidebar__single">
                            <h4 class="sidebar__title sidebar__form__title">Search</h4>
                            <form action="{{ route('citylife-talktime.index') }}" method="GET" class="sidebar__search">
                                <input type="text" name="search" placeholder="Search TalkTime episodes...">
                                <button type="submit" aria-label="search submit">
                                    <span class="icon-search"></span>
                                </button>
                            </form>
                        </div>

                        @if($relatedTalkTimes->isNotEmpty())
                            <div class="sidebar__posts-wrapper sidebar__single">
                                <h4 class="sidebar__title">Related Episodes</h4>
                                <ul class="sidebar__posts list-unstyled">
                                    @foreach($relatedTalkTimes as $related)
                                        <li class="sidebar__posts__item">
                                            <div class="sidebar__posts__image">
                                                <img src="{{ $related->image ? asset('media/' . $related->image) : asset('assets/images/defaults/talktime-default.jpg') }}" width="90" height="60" alt="{{ $related->title }}">
                                            </div>
                                            <div class="sidebar__posts__content">
                                                <p class="sidebar__posts__meta">
                                                    <span class="icon-user"></span>
                                                    {{ $related->host ?? 'By Admin' }}
                                                </p>
                                                <h4 class="sidebar__posts__title">
                                                    <a href="{{ route('citylife-talktime.show', $related->slug) }}">
                                                        {{ Str::limit($related->title, 50) }}
                                                    </a>
                                                </h4>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="sidebar__categories-wrapper sidebar__single">
                            <h4 class="sidebar__title">Hosts</h4>
                            <ul class="sidebar__categories list-unstyled">
                                @php
                                    $hosts = \App\Models\CityLifeTalkTime::published()
                                        ->whereNotNull('host')
                                        ->distinct()
                                        ->pluck('host');
                                    $hostCounts = [];
                                    foreach($hosts as $host) {
                                        $hostCounts[$host] = \App\Models\CityLifeTalkTime::published()->where('host', $host)->count();
                                    }
                                @endphp
                                @foreach($hostCounts as $host => $count)
                                    <li>
                                        <a href="{{ route('citylife-talktime.index', ['host' => $host]) }}">
                                            <span>{{ $host }}</span>
                                            <span>({{ $count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($talkTime->tags && count($talkTime->tags) > 0)
                            <div class="sidebar__tags-wrapper sidebar__single">
                                <h4 class="sidebar__title">Tags</h4>
                                <div class="sidebar__tags">
                                    @foreach($talkTime->tags as $tag)
                                        <a href="{{ route('citylife-talktime.index', ['search' => $tag]) }}">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="sidebar__single">
                            <h4 class="sidebar__title">Episode Information</h4>
                            <div class="sidebar__info">
                                @if($talkTime->host)
                                    <p><strong>Host:</strong> {{ $talkTime->host }}</p>
                                @endif
                                @if($talkTime->guest)
                                    <p><strong>Guest:</strong> {{ $talkTime->guest }}</p>
                                @endif
                                @if($talkTime->episode_date)
                                    <p><strong>Episode Date:</strong> {{ $talkTime->episode_date->format('F j, Y') }}</p>
                                @endif
                                @if($talkTime->duration)
                                    <p><strong>Duration:</strong> {{ $talkTime->duration }}</p>
                                @endif
                                @if($talkTime->views_count)
                                    <p><strong>Views:</strong> {{ $talkTime->views_count }}</p>
                                @endif
                                @if($talkTime->is_featured)
                                    <p><strong>Status:</strong> <span class="badge bg-primary">Featured</span></p>
                                @endif
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
