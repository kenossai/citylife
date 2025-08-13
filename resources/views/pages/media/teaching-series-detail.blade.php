<x-app-layout>
@section('title', $series->title . ' - Teaching Series')
@section('description', $series->summary ?? $series->excerpt)

<section class="page-header">
    <div class="page-header__bg" style="background-image: url('{{ $series->image_url }}');"></div>
    <div class="container">
        <h2 class="text-white">{{ $series->title }}</h2>
        <h2 class="page-header__title">Teaching Series</h2>
        <ul class="cleenhearts-breadcrumb list-unstyled">
            <li><i class="icon-home"></i> <a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('teaching-series.index') }}">Teaching Series</a></li>
            <li><span>{{ $series->title }}</span></li>
        </ul>
    </div>
</section>

<section class="blog-page section-space">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="blog-details">
                    <div class="blog-card blog-card-four wow fadeInUp animated" data-wow-delay="100ms" data-wow-duration="1500ms">
                        @if($series->video_url)
                            <!-- Video Player in Card Header -->
                            <div class="blog-card__image position-relative" style="z-index: 10;">
                                <style>
                                    .blog-card__image::before,
                                    .blog-card__image::after {
                                        display: none !important;
                                    }
                                </style>
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
                                        <div class="ratio ratio-16x9">
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                    title="{{ $series->title }}"
                                                    allowfullscreen
                                                    class="rounded"></iframe>
                                        </div>
                                    @else
                                        <div class="position-relative">
                                            <img src="{{ $series->image_url }}" alt="{{ $series->title }}">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <a href="{{ $series->video_url }}" target="_blank" class="cleenhearts-btn cleenhearts-btn--base">
                                                    <span class="cleenhearts-btn__icon-box">
                                                        <span class="cleenhearts-btn__icon-box__inner"><span class="icon-play"></span></span>
                                                    </span>
                                                    <span class="cleenhearts-btn__text">Watch Video</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="position-relative">
                                        <img src="{{ $series->image_url }}" alt="{{ $series->title }}">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <a href="{{ $series->video_url }}" target="_blank" class="cleenhearts-btn cleenhearts-btn--base">
                                                <span class="cleenhearts-btn__icon-box">
                                                    <span class="cleenhearts-btn__icon-box__inner"><span class="icon-play"></span></span>
                                                </span>
                                                <span class="cleenhearts-btn__text">Watch Video</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($series->pastor)
                                    <div class="blog-details__hall">
                                        <span>{{ $series->pastor }}</span>
                                    </div>
                                @endif
                                @if($series->series_date)
                                    <div class="blog-card__date">
                                        <span>{{ $series->series_date->format('d') }}</span>
                                        {{ $series->series_date->format('M') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Fallback to Image if no video -->
                            <div class="blog-card__image">
                                <img src="{{ $series->image_url }}" alt="{{ $series->title }}">
                                @if($series->pastor)
                                    <div class="blog-details__hall">
                                        <span>Pastor:</span>
                                        <span>{{ $series->pastor }}</span>
                                    </div>
                                @endif
                                @if($series->series_date)
                                    <div class="blog-card__date">
                                        <span>{{ $series->series_date->format('d') }}</span>
                                        {{ $series->series_date->format('M') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="blog-card-four__content">
                            <ul class="list-unstyled blog-card-four__meta">
                                @if($series->pastor)
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="icon-user"></span>
                                        {{ $series->pastor }}
                                    </a>
                                </li>
                                @endif
                                @if($series->sermon_notes)
                                <li>
                                    <a href="{{ $series->sermon_notes_url }}" target="_blank">
                                        <i class="fa-solid fa-cloud-arrow-down"></i>
                                        Download Sermon Notes
                                    </a>
                                </li>
                                @endif
                                @if ($series->audio_url)
                                    <li>
                                        <a href="{{ $series->audio_url }}" target="_blank">
                                            <i class="fa-solid fa-headphones"></i>
                                            Listen to Audio
                                        </a>
                                    </li>
                                @endif
                            </ul>
                            <h3 class="blog-card__title">{{ $series->title }}</h3>

                            @if($series->summary)
                                <p class="blog-card-four__text blog-card-four__text--one">{{ $series->summary }}</p>
                            @endif

                            @if($series->description)
                                <div class="blog-card-four__text blog-card-four__text--two">
                                    {!! $series->description !!}
                                </div>
                            @endif

                            @if($series->scripture_references)
                                <div class="blog-details__inner">
                                    <div class="blog-details__inner__content">
                                        <h4>Scripture References</h4>
                                        <p class="blog-details__inner__text">{{ $series->scripture_references }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="blog-details__meta">
                        @if($series->tags && count($series->tags) > 0)
                            <div class="blog-details__tags">
                                <h4 class="blog-details__meta__title">Tags:</h4>
                                <div class="blog-details__tags__box">
                                    @foreach($series->tags as $tag)
                                        <a href="{{ route('teaching-series.index', ['search' => $tag]) }}">{{ $tag }}</a>
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
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($series->title) }}" target="_blank">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                    <span class="sr-only">Twitter</span>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($series->title) }}%20{{ urlencode(request()->fullUrl()) }}" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                    <span class="sr-only">WhatsApp</span>
                                </a>
                                <a href="mailto:?subject={{ urlencode($series->title) }}&body={{ urlencode(request()->fullUrl()) }}">
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
                            <form action="{{ route('teaching-series.index') }}" method="GET" class="sidebar__search">
                                <input type="text" name="search" placeholder="Search Teaching Series...">
                                <button type="submit" aria-label="search submit">
                                    <span class="icon-search"></span>
                                </button>
                            </form>
                        </div>

                        @if($relatedSeries->isNotEmpty())
                            <div class="sidebar__posts-wrapper sidebar__single">
                                <h4 class="sidebar__title">Related Series</h4>
                                <ul class="sidebar__posts list-unstyled">
                                    @foreach($relatedSeries as $related)
                                        <li class="sidebar__posts__item">
                                            <div class="sidebar__posts__image">
                                                <img src="{{ $related->image_url }}" width="90" height="60" alt="{{ $related->title }}">
                                            </div>
                                            <div class="sidebar__posts__content">
                                                <p class="sidebar__posts__meta">
                                                    <span class="icon-user"></span>
                                                    {{ $related->pastor ?? 'By Admin' }}
                                                </p>
                                                <h4 class="sidebar__posts__title">
                                                    <a href="{{ route('teaching-series.show', $related->slug) }}">
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
                            <h4 class="sidebar__title">Categories</h4>
                            <ul class="sidebar__categories list-unstyled">
                                @php
                                    $categories = \App\Models\TeachingSeries::getCategories();
                                    $categoryCounts = [];
                                    foreach($categories as $category) {
                                        $categoryCounts[$category] = \App\Models\TeachingSeries::published()->where('category', $category)->count();
                                    }
                                @endphp
                                @foreach($categoryCounts as $category => $count)
                                    <li>
                                        <a href="{{ route('teaching-series.index', ['category' => $category]) }}">
                                            <span>{{ $category }}</span>
                                            <span>({{ $count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($series->tags && count($series->tags) > 0)
                            <div class="sidebar__tags-wrapper sidebar__single">
                                <h4 class="sidebar__title">Tags</h4>
                                <div class="sidebar__tags">
                                    @foreach($series->tags as $tag)
                                        <a href="{{ route('teaching-series.index', ['search' => $tag]) }}">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="sidebar__single">
                            <h4 class="sidebar__title">Series Information</h4>
                            <div class="sidebar__info">
                                @if($series->category)
                                    <p><strong>Category:</strong> {{ $series->category }}</p>
                                @endif
                                @if($series->series_date)
                                    <p><strong>Date:</strong> {{ $series->series_date->format('F j, Y') }}</p>
                                @endif
                                @if($series->formatted_duration)
                                    <p><strong>Duration:</strong> {{ $series->formatted_duration }}</p>
                                @endif
                                <p><strong>Views:</strong> {{ $series->views_count }}</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
