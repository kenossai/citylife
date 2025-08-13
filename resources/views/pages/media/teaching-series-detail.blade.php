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

<section class="product-details section-space-top section-space-bottom">
    <div class="container">
        <div class="row gutter-y-60">
            <div class="col-lg-8">
                <div class="product-details__content">
                    <!-- Media Section -->
                    @if($series->video_url || $series->audio_url)
                        <div class="product-details__media mb-5">
                            @if($series->video_url)
                                <div class="video-wrapper mb-4">
                                    <h4 class="mb-3">Watch Video</h4>
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
                                                        allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <a href="{{ $series->video_url }}" target="_blank" class="btn btn-primary">
                                                <i class="icon-play me-2"></i>Watch Video
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ $series->video_url }}" target="_blank" class="btn btn-primary">
                                            <i class="icon-play me-2"></i>Watch Video
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if($series->audio_url)
                                <div class="audio-wrapper">
                                    <h4 class="mb-3">Listen to Audio</h4>
                                    <a href="{{ $series->audio_url }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="icon-music me-2"></i>Listen to Audio
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Series Information -->
                    <div class="product-details__info">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h3 class="product-details__title">{{ $series->title }}</h3>
                                @if($series->pastor)
                                    <p class="product-details__pastor mb-2">
                                        <strong>Speaker:</strong> {{ $series->pastor }}
                                    </p>
                                @endif
                                @if($series->series_date)
                                    <p class="product-details__date mb-2">
                                        <strong>Date:</strong> {{ $series->series_date->format('F j, Y') }}
                                    </p>
                                @endif
                                @if($series->formatted_duration)
                                    <p class="product-details__duration mb-2">
                                        <strong>Duration:</strong> {{ $series->formatted_duration }}
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($series->category)
                                    <p class="mb-2">
                                        <strong>Category:</strong>
                                        <span class="badge badge-primary">{{ $series->category }}</span>
                                    </p>
                                @endif
                                @if($series->tags && count($series->tags) > 0)
                                    <p class="mb-2">
                                        <strong>Tags:</strong>
                                        @foreach($series->tags as $tag)
                                            <span class="badge badge-outline me-1">{{ $tag }}</span>
                                        @endforeach
                                    </p>
                                @endif
                                <p class="mb-2">
                                    <strong>Views:</strong> {{ number_format($series->views_count) }}
                                </p>
                            </div>
                        </div>

                        @if($series->scripture_references)
                            <div class="scripture-references mb-4">
                                <h5>Scripture References</h5>
                                <p class="text-muted">{{ $series->scripture_references }}</p>
                            </div>
                        @endif

                        @if($series->description)
                            <div class="product-details__description">
                                <h5>Description</h5>
                                <div class="content">
                                    {!! $series->description !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <aside class="product__sidebar">
                    <!-- Share Section -->
                    <div class="product__sidebar__item">
                        <h3 class="product__sidebar__title">Share This Series</h3>
                        <div class="social-share">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                               target="_blank" class="btn btn-outline-primary btn-sm me-2 mb-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($series->title) }}"
                               target="_blank" class="btn btn-outline-info btn-sm me-2 mb-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($series->title . ' - ' . request()->fullUrl()) }}"
                               target="_blank" class="btn btn-outline-success btn-sm mb-2">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Related Series -->
                    @if($relatedSeries->isNotEmpty())
                        <div class="product__sidebar__item">
                            <h3 class="product__sidebar__title">Related Series</h3>
                            @foreach($relatedSeries as $related)
                                <div class="related-series-item mb-3 pb-3 border-bottom">
                                    <a href="{{ route('teaching-series.show', $related->slug) }}" class="d-flex text-decoration-none">
                                        <img src="{{ $related->image_url }}" alt="{{ $related->title }}"
                                             class="me-3 rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ Str::limit($related->title, 50) }}</h6>
                                            @if($related->pastor)
                                                <small class="text-muted d-block">{{ $related->pastor }}</small>
                                            @endif
                                            @if($related->series_date)
                                                <small class="text-muted">{{ $related->series_date->format('M j, Y') }}</small>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="product__sidebar__item">
                        <h3 class="product__sidebar__title">Quick Links</h3>
                        <div class="d-grid gap-2">
                            <a href="{{ route('teaching-series.index') }}" class="btn btn-outline-primary">
                                <i class="icon-arrow-left me-2"></i>All Teaching Series
                            </a>
                            @if($series->category)
                                <a href="{{ route('teaching-series.index', ['category' => $series->category]) }}"
                                   class="btn btn-outline-secondary">
                                    <i class="icon-grid me-2"></i>More {{ $series->category }}
                                </a>
                            @endif
                            @if($series->pastor)
                                <a href="{{ route('teaching-series.index', ['pastor' => $series->pastor]) }}"
                                   class="btn btn-outline-info">
                                    <i class="icon-user me-2"></i>More by {{ $series->pastor }}
                                </a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
</x-app-layout>
